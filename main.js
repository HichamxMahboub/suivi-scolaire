const { app, BrowserWindow } = require('electron');
const path = require('path');
let serverProcess;

function createWindow() {
  const win = new BrowserWindow({
    width: 1200,
    height: 800,
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true,
    }
  });
  win.loadURL('http://127.0.0.1:8000');
}

app.whenReady().then(() => {
  // Start Laravel server
  const { spawn } = require('child_process');
  serverProcess = spawn('php', ['artisan', 'serve'], { cwd: process.cwd(), shell: true, stdio: 'ignore' });

  createWindow();

  app.on('activate', () => {
    if (BrowserWindow.getAllWindows().length === 0) createWindow();
  });
});

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') {
    if (serverProcess) serverProcess.kill();
    app.quit();
  }
});
