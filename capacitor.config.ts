import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  appId: 'com.apotreonesime.mpf',
  appName: 'MPF',
  webDir: 'public_mobile',
  server: {
    url: 'https://mpf.apotreonesime.com',
    cleartext: true
  }
};

export default config;
