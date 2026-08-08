import * as FileSystem from 'expo-file-system/legacy';
import { Asset } from 'expo-asset';

export interface MedicalResult {
  id: number;
  service_name: string;
  service_category?: string;
  status: string;
  findings?: string;
  doctor_remarks?: string;
  doctor_name?: string;
  doctor_license?: string;
  patient_name?: string;
  image_url?: string | null;
  image_base64?: string | null;
  created_at: string;
}

// ─── Logo Caching ─────────────────────────────────────────────────────────────
let cachedLeftLogo: string | null = null;
let cachedRightLogo: string | null = null;

export async function getLeftLogoBase64(): Promise<string> {
  if (cachedLeftLogo) return cachedLeftLogo;
  try {
    const asset = Asset.fromModule(require('../../assets/images/mho123.png'));
    await asset.downloadAsync();
    const base64 = await FileSystem.readAsStringAsync(asset.localUri!, { encoding: 'base64' });
    cachedLeftLogo = `data:image/png;base64,${base64}`;
    return cachedLeftLogo;
  } catch (e) {
    console.error('[PDF Utils] Error loading left logo:', e);
    return '';
  }
}

export async function getRightLogoBase64(): Promise<string> {
  if (cachedRightLogo) return cachedRightLogo;
  try {
    const asset = Asset.fromModule(require('../../assets/images/OPOL.png'));
    await asset.downloadAsync();
    const base64 = await FileSystem.readAsStringAsync(asset.localUri!, { encoding: 'base64' });
    cachedRightLogo = `data:image/png;base64,${base64}`;
    return cachedRightLogo;
  } catch (e) {
    console.error('[PDF Utils] Error loading right logo:', e);
    return '';
  }
}

// ─── Shared CSS ───────────────────────────────────────────────────────────────
export const BASE_CSS = `
  * { box-sizing: border-box; margin: 0; padding: 0; }
  body { 
    font-family: 'Times New Roman', Times, serif; 
    color: #111; 
    background: #fff; 
    padding: 30px 40px; 
    font-size: 10pt;
    line-height: 1.4;
  }
  
  .watermark {
    position: fixed; top: 50%; left: 50%;
    transform: translate(-50%, -50%) rotate(-30deg);
    font-size: 90pt; color: rgba(0,0,0,0.03); font-weight: 900;
    pointer-events: none; white-space: nowrap; z-index: -1;
  }
  
  .official-header {
    display: flex; 
    align-items: center; 
    justify-content: space-between;
    border-bottom: 2px solid #000; 
    padding-bottom: 12px; 
    margin-bottom: 16px;
  }
  
  .official-header .logo { 
    width: 65px; 
    height: 65px; 
    object-fit: contain;
    flex-shrink: 0;
  }
  
  .official-header .center { 
    text-align: center; 
    flex: 1; 
    padding: 0 16px; 
  }
  
  .official-header .center .govt { 
    font-size: 8.5pt; 
    color: #333; 
    font-weight: 600;
    letter-spacing: 0.5px;
    line-height: 1.3;
  }
  
  .official-header .center h1 { 
    font-size: 13pt; 
    font-weight: 900; 
    margin: 3px 0 2px 0;
    text-transform: uppercase; 
    letter-spacing: 1.5px;
  }
  
  .official-header .center .address { 
    font-size: 8.5pt; 
    color: #444; 
    font-weight: 600;
    line-height: 1.3;
  }
  
  .official-header .center .dept { 
    font-size: 9.5pt; 
    font-weight: 800; 
    margin-top: 3px; 
    letter-spacing: 1.2px; 
    text-transform: uppercase;
  }
  
  .patient-info-group {
    margin-bottom: 14px;
    display: flex;
    gap: 40px;
    flex-wrap: wrap;
  }
  
  .patient-info-item {
    display: inline-flex;
    gap: 8px;
  }
  
  .patient-info-label {
    font-weight: 700;
    font-size: 10pt;
  }
  
  .patient-info-value {
    font-weight: 600;
    font-size: 10pt;
  }
  
  .section-header {
    font-size: 11pt;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 2px;
    margin: 14px 0 8px 0;
    padding-bottom: 3px;
    border-bottom: 2px solid #000;
  }
  
  .findings-list, .impression-list {
    list-style: none;
    padding: 0;
    margin: 6px 0 12px 0;
  }
  
  .findings-list li, .impression-list li {
    font-size: 10pt;
    line-height: 1.6;
    padding: 4px 0 4px 20px;
    position: relative;
    font-weight: 500;
  }
  
  .findings-list li::before, .impression-list li::before {
    content: '•';
    position: absolute;
    left: 4px;
    font-weight: 900;
    font-size: 11pt;
  }
  
  .signatures {
    display: flex;
    justify-content: space-between;
    margin-top: 36px;
    gap: 40px;
  }
  
  .sig-block {
    text-align: center;
    flex: 1;
  }
  
  .sig-line {
    border-top: 1.5px solid #000;
    margin-bottom: 6px;
    width: 100%;
    height: 30px;
  }
  
  .sig-name {
    font-size: 10pt;
    font-weight: 700;
    margin-bottom: 2px;
  }
  
  .sig-title {
    font-size: 8.5pt;
    color: #555;
    line-height: 1.4;
  }
  
  .footer {
    position: fixed;
    bottom: 24px;
    left: 40px;
    right: 40px;
    text-align: center;
    font-size: 7pt;
    color: #888;
    border-top: 1px solid #ddd;
    padding-top: 6px;
  }
  
  .page-break {
    page-break-before: always;
  }
  
  .full-page-image-wrapper {
    width: 100%;
    height: 100vh;
    display: flex;
    flex-direction: column;
    margin: 0;
    padding: 0;
  }
  
  .full-page-image-title {
    font-size: 11.5pt;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 2px;
    padding: 10px 20px;
    text-align: center;
    flex-shrink: 0;
    margin-bottom: 8px;
  }
  
  .full-page-image-container {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    padding: 0;
    margin: 0;
  }
  
  .full-page-image-container img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    display: block;
  }
  
  .no-image-notice {
    text-align: center;
    padding: 20px;
    border: 2px dashed #ccc;
    color: #999;
    font-style: italic;
    font-size: 10pt;
    margin: 16px 0;
  }
`;

// ─── Parse findings JSON ──────────────────────────────────────────────────────
export function parseFindings(raw: string | undefined): any {
  if (!raw) return {};
  try {
    return typeof raw === 'string' ? JSON.parse(raw) : raw;
  } catch {
    return {};
  }
}

// ─── Format Date & Time ───────────────────────────────────────────────────────
export function formatDateAndTime(dateString: string): { date: string; time: string } {
  const dateObj = new Date(dateString);
  const date = dateObj.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
  const time = dateObj.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
  return { date, time };
}

// ─── Build Header HTML ────────────────────────────────────────────────────────
export function buildHeaderHtml(leftLogo: string, rightLogo: string, sectionLabel: string): string {
  return `
    <div class="watermark">MHO</div>
    <div class="official-header">
      ${leftLogo ? `<img src="${leftLogo}" class="logo" />` : '<div style="width:65px;flex-shrink:0;"></div>'}
      <div class="center">
        <div class="govt">Republic of the Philippines</div>
        <div class="govt">Province of Misamis Oriental</div>
        <h1>Opol Municipal Health Center</h1>
        <div class="address">Taboc, Opol, Misamis Oriental</div>
        <div class="dept">${sectionLabel}</div>
      </div>
      ${rightLogo ? `<img src="${rightLogo}" class="logo" />` : '<div style="width:65px;flex-shrink:0;"></div>'}
    </div>
  `;
}

// ─── Build Patient Info HTML ──────────────────────────────────────────────────
export function buildPatientInfoHtml(patientName: string, serviceName: string, date: string, time: string): string {
  return `
    <div class="patient-info-group">
      <div class="patient-info-item">
        <span class="patient-info-label">Patient Name:</span>
        <span class="patient-info-value">${patientName || 'N/A'}</span>
      </div>
      <div class="patient-info-item">
        <span class="patient-info-label">Date:</span>
        <span class="patient-info-value">${date}</span>
      </div>
      <div class="patient-info-item">
        <span class="patient-info-label">Time:</span>
        <span class="patient-info-value">${time}</span>
      </div>
    </div>
    <div class="patient-info-group">
      <div class="patient-info-item">
        <span class="patient-info-label">Service/Clinic:</span>
        <span class="patient-info-value">${serviceName}</span>
      </div>
    </div>
  `;
}

// ─── Build Signatures HTML ────────────────────────────────────────────────────
export function buildSignaturesHtml(doctorName: string, doctorLicense: string): string {
  const now = new Date();
  const nowDate = now.toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
  const nowTime = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

  return `
    <div class="signatures">
      <div class="sig-block">
        <div class="sig-line"></div>
        <div class="sig-name">${doctorName || 'GLEZEAL J. MACALISANG, RMT'}</div>
        <div class="sig-title">Medical Technologist<br/>Lic. No. ${doctorLicense || '0108927'}</div>
      </div>
      <div class="sig-block">
        <div class="sig-line"></div>
        <div class="sig-name">RAMON M. NERY, M.D.</div>
        <div class="sig-title">Pathologist<br/>Lic. No. 52586</div>
      </div>
    </div>
    <div class="footer">
      This document is computer-generated and valid without signature.<br/>
      Generated on ${nowDate} at ${nowTime}
    </div>
  `;
}

// ─── Load Image as Base64 ──────────────────────────────────────────────────────
export async function loadImageAsBase64(
  imageUrl: string | null | undefined,
  imageBase64: string | null | undefined
): Promise<string | null> {
  console.log('[PDF Utils] Loading image...');

  if (imageBase64 && typeof imageBase64 === 'string') {
    if (imageBase64.startsWith('data:image')) {
      console.log('[PDF Utils] ✅ Using base64 from API');
      return imageBase64;
    }
    if (imageBase64.length > 100) {
      return `data:image/jpeg;base64,${imageBase64}`;
    }
  }

  if (!imageUrl || imageUrl === 'null' || imageUrl === 'undefined' || imageUrl.trim() === '') {
    console.log('[PDF Utils] No image URL provided');
    return null;
  }

  let url = imageUrl.trim();
  if (url.includes('localhost') || url.includes('127.0.0.1')) {
    const ipAddress = '192.168.1.100';
    url = url.replace('localhost', ipAddress).replace('127.0.0.1', ipAddress);
  }

  try {
    const ext = url.split('?')[0].split('.').pop()?.toLowerCase() || 'jpg';
    const safeExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext) ? ext : 'jpg';
    const mimeType = safeExt === 'png' ? 'image/png' : safeExt === 'gif' ? 'image/gif' : 'image/jpeg';

    const destUri = `${FileSystem.cacheDirectory}result_img_${Date.now()}.${safeExt}`;
    const downloadResult = await FileSystem.downloadAsync(url, destUri);

    if (downloadResult.status !== 200) {
      console.error('[PDF Utils] Download failed:', downloadResult.status);
      return null;
    }

    const base64 = await FileSystem.readAsStringAsync(downloadResult.uri, { encoding: 'base64' });
    console.log('[PDF Utils] Image loaded');
    return `data:${mimeType};base64,${base64}`;
  } catch (e) {
    console.error('[PDF Utils] Image load error:', e);
    return null;
  }
}