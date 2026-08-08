import * as Print from 'expo-print';
import * as Sharing from 'expo-sharing';
import { Alert } from 'react-native';
import {
  MedicalResult,
  getLeftLogoBase64,
  getRightLogoBase64,
  BASE_CSS,
  parseFindings,
  formatDateAndTime,
  buildHeaderHtml,
  buildSignaturesHtml,
} from './pdfUtils';

const LAB_CSS = `
  .section-header { color: #1a5c3a; border-bottom: 2px solid #1a5c3a; }
  .side-by-side { display: flex; border: 1.5px solid #b2d8c7; }
  .side-by-side .lab-section-table { flex: 1; border: none; }
  .side-by-side .lab-section-table:first-child { border-right: 1.5px solid #b2d8c7; }
  .full-width-section .lab-section-table { width: 100%; border: 1.5px solid #b2d8c7; border-top: none; }
  .full-width-section:first-child .lab-section-table { border-top: 1.5px solid #b2d8c7; }
  .lab-section-table { width: 100%; border-collapse: collapse; margin-bottom: 0; }
  .lab-header { background: #e6f4ee; color: #1a5c3a; font-size: 10pt; font-weight: 800; letter-spacing: 1.2px; text-transform: uppercase; padding: 8px 12px; text-align: left; border-bottom: 1.5px solid #b2d8c7; }
  .lab-label { padding: 7px 12px; font-size: 9.5pt; font-weight: 600; text-transform: uppercase; color: #222; border-bottom: 1px solid #e0f0e8; width: 52%; }
  .lab-unit { padding: 7px 4px; font-size: 8.5pt; color: #2e7d50; font-style: italic; border-bottom: 1px solid #e0f0e8; width: 12%; white-space: nowrap; text-align: center; }
  .lab-value { padding: 7px 12px; font-size: 10.5pt; font-weight: 700; border-bottom: 1px solid #e0f0e8; }
  tbody tr:last-child td { border-bottom: none; }
`;

// ─── Parse lab findings into sections ──────────────────────────────────────────
function parseLabFindingsToSections(findings: any, serviceName: string): any[] {
  const sLower = serviceName.toLowerCase();
  const rawText: string = findings.findings_text || findings.findings || '';
  const lines = rawText.split('\n').map((l: string) => l.trim()).filter(Boolean);

  if (findings.sections && Array.isArray(findings.sections)) {
    return findings.sections;
  }

  // ── URINALYSIS ──
  if (sLower.includes('urinalysis') || sLower.includes('urine')) {
    const physical = [
      { label: 'Color', value: lines[0] || '' },
      { label: 'Transparency', value: lines[1] || '' },
      { label: 'Reaction (pH)', value: lines[2] || '' },
      { label: 'Specific Gravity', value: lines[3] || '' },
    ];
    const chemical = [
      { label: 'Albumin', value: lines[4] || '' },
      { label: 'Sugar', value: lines[5] || '' },
      { label: 'Blood', value: lines[6] || '' },
    ];
    const microscopic = [
      { label: 'Pus Cells', unit: '/HPF', value: lines[7] || '' },
      { label: 'Red Blood Cells', unit: '/HPF', value: lines[8] || '' },
      { label: 'Epithelial Cells', unit: '/HPF', value: lines[9] || '' },
      { label: 'Mucus Threads', value: lines[10] || '' },
      { label: 'Bacteria', value: lines[11] || '' },
      { label: 'Casts', value: lines[12] || '' },
      { label: 'Crystals', value: lines[13] || '' },
    ];
    return [
      { title: 'PHYSICAL EXAMINATION', rows: physical, sideBySide: true },
      { title: 'CHEMICAL EXAMINATION', rows: chemical, sideBySide: true },
      { title: 'MICROSCOPIC EXAMINATION', rows: microscopic, sideBySide: false },
    ];
  }

  // ── FECALYSIS ──
  if (sLower.includes('fecalysis') || sLower.includes('fecal') || sLower.includes('stool')) {
    const physical = [
      { label: 'Color', value: lines[0] || '' },
      { label: 'Consistency', value: lines[1] || '' },
    ];
    const microscopic = [
      { label: 'Pus Cells', unit: '/HPF', value: lines[2] || '' },
      { label: 'RBC', unit: '/HPF', value: lines[3] || '' },
      { label: 'Bacteria', value: lines[4] || '' },
      { label: 'Fat Globules', value: lines[5] || '' },
      { label: 'Amoeba', value: lines[6] || '' },
      { label: 'Ova / Egg', value: lines[7] || '' },
      { label: 'Result', value: lines[8] || '' },
    ];
    return [
      { title: 'PHYSICAL EXAMINATION', rows: physical, sideBySide: false },
      { title: 'MICROSCOPIC EXAMINATION', rows: microscopic, sideBySide: false },
    ];
  }

  // ── CBC ──
  if (sLower.includes('cbc') || sLower.includes('complete blood')) {
    const rows = [
      { label: 'Hemoglobin', value: lines[0] || '', unit: 'g/dL' },
      { label: 'Hematocrit', value: lines[1] || '', unit: '%' },
      { label: 'RBC Count', value: lines[2] || '', unit: 'x10⁶/µL' },
      { label: 'WBC Count', value: lines[3] || '', unit: 'x10³/µL' },
      { label: 'Platelet Count', value: lines[4] || '', unit: 'x10³/µL' },
      { label: 'Neutrophils', value: lines[5] || '', unit: '%' },
      { label: 'Lymphocytes', value: lines[6] || '', unit: '%' },
      { label: 'Monocytes', value: lines[7] || '', unit: '%' },
      { label: 'Eosinophils', value: lines[8] || '', unit: '%' },
      { label: 'Basophils', value: lines[9] || '', unit: '%' },
    ];
    return [{ title: 'COMPLETE BLOOD COUNT', rows, sideBySide: false }];
  }

  // ── LIPID PROFILE ──
  if (sLower.includes('lipid') || sLower.includes('cholesterol')) {
    const rows = [
      { label: 'Total Cholesterol', value: lines[0] || '', unit: 'mg/dL' },
      { label: 'Triglycerides', value: lines[1] || '', unit: 'mg/dL' },
      { label: 'HDL', value: lines[2] || '', unit: 'mg/dL' },
      { label: 'LDL', value: lines[3] || '', unit: 'mg/dL' },
    ];
    return [{ title: 'LIPID PROFILE', rows, sideBySide: false }];
  }

  // ── GENERIC FALLBACK ──
  if (lines.length > 0) {
    const rows = lines.map((v, i) => ({ label: `Result ${i + 1}`, value: v }));
    return [{ title: 'LABORATORY RESULTS', rows, sideBySide: false }];
  }

  return [];
}

// ─── Build lab table HTML ─────────────────────────────────────────────────────
function buildLabTableHtml(sections: any[]): string {
  if (!sections.length) return '<p style="color:#999;font-style:italic;padding:12px;">No laboratory data available.</p>';

  let html = '';
  let i = 0;

  while (i < sections.length) {
    const s = sections[i];
    const next = sections[i + 1];

    if (s.sideBySide && next?.sideBySide) {
      html += `<div class="side-by-side">${renderSection(s)}${renderSection(next)}</div>`;
      i += 2;
    } else {
      html += `<div class="full-width-section">${renderSection(s)}</div>`;
      i += 1;
    }
  }

  return html;
}

function renderSection(section: any): string {
  const rows = (section.rows || [])
    .map((row: any) => `
      <tr>
        <td class="lab-label">${row.label || ''}</td>
        ${row.unit !== undefined ? `<td class="lab-unit">${row.unit || ''}</td>` : ''}
        <td class="lab-value">${row.value ?? ''}</td>
      </tr>
    `)
    .join('');

  return `
    <table class="lab-section-table">
      <thead><tr><th colspan="3" class="lab-header">${section.title}</th></tr></thead>
      <tbody>${rows}</tbody>
    </table>
  `;
}

async function generateLaboratoryPdf(item: MedicalResult, leftLogo: string, rightLogo: string): Promise<string> {
  console.log('[PDF Lab] Generating Laboratory PDF...');

  const { date, time } = formatDateAndTime(item.created_at);
  const findings = parseFindings(item.findings);
  const sections = parseLabFindingsToSections(findings, item.service_name);
  const tableHtml = buildLabTableHtml(sections);

  const labPatientInfo = `
    <div class="patient-info-group">
      <div class="patient-info-item">
        <span class="patient-info-label">Patient Name:</span>
        <span class="patient-info-value">${item.patient_name || 'N/A'}</span>
      </div>
      <div class="patient-info-item">
        <span class="patient-info-label">Service:</span>
        <span class="patient-info-value">${item.service_name}</span>
      </div>
    </div>
    <div class="patient-info-group">
      <div class="patient-info-item">
        <span class="patient-info-label">Date:</span>
        <span class="patient-info-value">${date}</span>
      </div>
      <div class="patient-info-item">
        <span class="patient-info-label">Time:</span>
        <span class="patient-info-value">${time}</span>
      </div>
    </div>
  `;

  const html = `<!DOCTYPE html><html><head><meta charset="utf-8"/>
    <style>${BASE_CSS}${LAB_CSS}</style></head>
    <body>
      ${buildHeaderHtml(leftLogo, rightLogo, 'LABORATORY SECTION')}
      ${labPatientInfo}
      ${tableHtml}
      ${buildSignaturesHtml(item.doctor_name || '', item.doctor_license || '')}
    </body></html>`;

  return html;
}

export async function downloadLabPdf(item: MedicalResult) {
  try {
    console.log('[PDF Lab] ==========================================');
    console.log('[PDF Lab] STARTING LABORATORY PDF GENERATION');
    console.log('[PDF Lab] ==========================================');

    const [leftLogo, rightLogo] = await Promise.all([getLeftLogoBase64(), getRightLogoBase64()]);
    const html = await generateLaboratoryPdf(item, leftLogo, rightLogo);

    console.log('[PDF Lab] HTML generated, printing to PDF...');
    const { uri } = await Print.printToFileAsync({ html, base64: false, width: 612, height: 792 });
    console.log('[PDF Lab] PDF saved to:', uri);

    const canShare = await Sharing.isAvailableAsync();
    if (canShare) {
      await Sharing.shareAsync(uri, {
        mimeType: 'application/pdf',
        dialogTitle: `${item.service_name} Report`,
        UTI: 'com.adobe.pdf',
      });
    } else {
      Alert.alert('Saved', `PDF saved to:\n${uri}`);
    }
    console.log('[PDF Lab]  PDF GENERATION COMPLETE');
  } catch (e) {
    Alert.alert('Error', 'Could not generate Laboratory PDF. Please try again.');
    console.error('[PDF Lab] Error:', e);
  }
}