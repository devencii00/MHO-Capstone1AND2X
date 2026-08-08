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
  buildPatientInfoHtml,
  loadImageAsBase64,
} from './pdfUtils';

const ULTRASOUND_CSS = `
  .section-header {
    border-bottom: none;
  }
  .findings-list,
  .impression-list {
    border: none;
    border-bottom: none;
  }
  .full-page-image-wrapper {
    background: #fff;
  }
  .full-page-image-container {
    background: #fff;
    border: none;
  }
  .full-page-image-container img {
    object-fit: cover;
    width: 90%;
    height: 90%;
  }
`;

async function generateUltrasoundPdf(item: MedicalResult, leftLogo: string, rightLogo: string): Promise<string> {
  console.log('[PDF Ultrasound] Generating Ultrasound PDF...');

  const { date, time } = formatDateAndTime(item.created_at);
  const findings = parseFindings(item.findings);
  const findingsText: string = findings.findings_text || findings.findings || '';
  const impressionText: string = findings.impression || '';
  const findingsLines = findingsText.split('\n').map((l: string) => l.trim()).filter(Boolean);
  const impressionLines = impressionText.split('\n').map((l: string) => l.trim()).filter(Boolean);

  const imageBase64 = (item as any).image_base64;
  const resultImageBase64 = await loadImageAsBase64(item.image_url, imageBase64);

  // Image fills the entire second page edge-to-edge, no title, no black bars.
  const imagePage = resultImageBase64
    ? `<div class="page-break"></div>
       <div class="full-page-image-wrapper">
         <div class="full-page-image-container">
           <img src="${resultImageBase64}" />
         </div>
       </div>`
    : `<div class="page-break"></div><div class="no-image-notice">No ultrasound image attached to this result.</div>`;

  // No signature block — removed entirely (Medical Technologist / Pathologist).
  const html = `<!DOCTYPE html><html><head><meta charset="utf-8"/>
    <style>${BASE_CSS}${ULTRASOUND_CSS}</style></head>
    <body>
      ${buildHeaderHtml(leftLogo, rightLogo, 'ULTRASOUND SECTION')}
      ${buildPatientInfoHtml(item.patient_name || '', item.service_name, date, time)}
      ${findingsLines.length > 0 ? `<div class="section-header">FINDINGS</div><ul class="findings-list">${findingsLines.map((l: string) => `<li>${l}</li>`).join('')}</ul>` : ''}
      ${impressionLines.length > 0 ? `<div class="section-header">IMPRESSION</div><ul class="impression-list">${impressionLines.map((l: string) => `<li>${l}</li>`).join('')}</ul>` : ''}
      ${imagePage}
    </body></html>`;

  return html;
}

export async function downloadUltrasoundPdf(item: MedicalResult) {
  try {
    console.log('[PDF Ultrasound] ==========================================');
    console.log('[PDF Ultrasound] STARTING ULTRASOUND PDF GENERATION');
    console.log('[PDF Ultrasound] ==========================================');

    const [leftLogo, rightLogo] = await Promise.all([getLeftLogoBase64(), getRightLogoBase64()]);
    const html = await generateUltrasoundPdf(item, leftLogo, rightLogo);

    console.log('[PDF Ultrasound] HTML generated, printing to PDF...');
    const { uri } = await Print.printToFileAsync({ html, base64: false, width: 612, height: 792 });
    console.log('[PDF Ultrasound] PDF saved to:', uri);

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
    console.log('[PDF Ultrasound]  PDF GENERATION COMPLETE');
  } catch (e) {
    Alert.alert('Error', 'Could not generate Ultrasound PDF. Please try again.');
    console.error('[PDF Ultrasound] Error:', e);
  }
}

