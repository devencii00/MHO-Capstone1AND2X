import { MedicalResult } from './pdfUtils';
import { downloadXrayPdf } from './pdfXray';
import { downloadUltrasoundPdf } from './pdfUltrasound';
import { downloadLabPdf } from './pdfLab';

// ─── Detect report type ───────────────────────────────────────────────────────
function getReportType(item: MedicalResult): 'xray' | 'ultrasound' | 'laboratory' {
  const category = (item.service_category || '').toLowerCase().trim();
  const name = (item.service_name || '').toLowerCase().trim();
  const combined = `${category} ${name}`;

  if (
    combined.includes('xray') ||
    combined.includes('x-ray') ||
    combined.includes('x_ray') ||
    combined.includes('radiograph')
  ) {
    return 'xray';
  }

  if (
    combined.includes('ultrasound') ||
    combined.includes('utz') ||
    combined.includes('abdomen') ||
    combined.includes('pelvic') ||
    combined.includes('transvaginal') ||
    combined.includes('transrectal') ||
    combined.includes('biophysical') ||
    combined.includes('thyroid') ||
    combined.includes('breast') ||
    combined.includes('scrotal') ||
    combined.includes('kub') ||
    combined.includes('doppler') ||
    combined.includes('soft tissue') ||
    combined.includes('neck') ||
    combined.includes('hbt')
  ) {
    return 'ultrasound';
  }

  return 'laboratory';
}

// ─── Main export - routes to correct PDF generator ────────────────────────────
export async function downloadPdf(item: MedicalResult) {
  try {
    console.log('[PDF] Report type detected...');
    const reportType = getReportType(item);
    console.log('[PDF] Type:', reportType);

    switch (reportType) {
      case 'xray':
        await downloadXrayPdf(item);
        break;
      case 'ultrasound':
        await downloadUltrasoundPdf(item);
        break;
      case 'laboratory':
        await downloadLabPdf(item);
        break;
    }
  } catch (e) {
    console.error('[PDF] Error in main download:', e);
    throw e;
  }
}

// ─── Export individual generators for direct use ──────────────────────────────
export { downloadXrayPdf } from './pdfXray';
export { downloadUltrasoundPdf } from './pdfUltrasound';
export { downloadLabPdf } from './pdfLab';
export type { MedicalResult } from './pdfUtils';