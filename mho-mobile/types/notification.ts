export interface PushMessageData {
  type?: string;
  queue_id?: number | string;
  patient_id?: number | string;
}

export interface PusherMessagePayload {
  message: string;
  sender: string;
  timestamp: string;
}