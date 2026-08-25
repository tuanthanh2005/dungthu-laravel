const { app, BrowserWindow, shell, Menu } = require('electron');
const path = require('path');

const TARGET_URL = process.env.APP_URL || 'https://dungthu.com';

// 1. Quản lý Single Instance Lock (Tránh mở trùng lặp nhiều cửa sổ khi nhấp kép nhiều lần)
const gotTheLock = app.requestSingleInstanceLock();
let mainWindow = null;

if (!gotTheLock) {
  app.quit();
} else {
  app.on('second-instance', () => {
    if (mainWindow) {
      if (mainWindow.isMinimized()) mainWindow.restore();
      mainWindow.focus();
    }
  });

  app.whenReady().then(() => {
    createWindow();

    app.on('activate', function () {
      if (BrowserWindow.getAllWindows().length === 0) createWindow();
    });
  });
}

function createWindow() {
  mainWindow = new BrowserWindow({
    width: 1360,
    height: 860,
    minWidth: 900,
    minHeight: 600,
    title: 'Dùng Thử AI - Desktop App',
    icon: path.join(__dirname, '../../public/images/dungthu.png'),
    autoHideMenuBar: false,
    webPreferences: {
      nodeIntegration: false,
      contextIsolation: true,
      sandbox: true,
      webSecurity: true
    }
  });

  // Set Custom UserAgent
  mainWindow.webContents.setUserAgent(
    mainWindow.webContents.getUserAgent() + ' DungThuDesktopApp/1.0'
  );

  // Load URL
  mainWindow.loadURL(TARGET_URL).catch((err) => {
    console.error('Failed to load URL:', err);
  });

  // Xử lý lỗi tải trang (Ví dụ mất mạng)
  mainWindow.webContents.on('did-fail-load', () => {
    mainWindow.loadURL('data:text/html;charset=utf-8,' + encodeURIComponent(`
      <div style="font-family: Arial, sans-serif; text-align: center; padding: 50px;">
        <h2>Không thể kết nối tới server</h2>
        <p>Vui lòng kiểm tra kết nối Internet và thử lại.</p>
        <button onclick="window.location.reload()" style="padding: 10px 20px; font-size: 16px; cursor: pointer;">Tải lại trang</button>
      </div>
    `));
  });

  // Mở link ngoài bằng trình duyệt mặc định của máy tính (Zalo, Telegram, Mailto, v.v.)
  mainWindow.webContents.setWindowOpenHandler(({ url }) => {
    if (!url.startsWith(TARGET_URL)) {
      shell.openExternal(url);
      return { action: 'deny' };
    }
    return { action: 'allow' };
  });

  // Tùy chỉnh Menu Bar
  const menuTemplate = [
    {
      label: 'Ứng Dụng',
      submenu: [
        { label: 'Trang Chủ', click: () => mainWindow.loadURL(TARGET_URL) },
        { label: 'Tải Lại (Reload)', accelerator: 'CmdOrCtrl+R', click: () => mainWindow.reload() },
        { type: 'separator' },
        { label: 'Thoát App', accelerator: 'CmdOrCtrl+Q', role: 'quit' }
      ]
    },
    {
      label: 'Điều Hướng',
      submenu: [
        { label: 'Quay Lại (Back)', accelerator: 'Alt+Left', click: () => { if (mainWindow.webContents.canGoBack()) mainWindow.webContents.goBack(); } },
        { label: 'Tiến Tới (Forward)', accelerator: 'Alt+Right', click: () => { if (mainWindow.webContents.canGoForward()) mainWindow.webContents.goForward(); } }
      ]
    },
    {
      label: 'Cửa Sổ',
      submenu: [
        { role: 'minimize', label: 'Thu Nhỏ' },
        { role: 'togglefullscreen', label: 'Toàn Màn Hình' }
      ]
    }
  ];

  const menu = Menu.buildFromTemplate(menuTemplate);
  Menu.setApplicationMenu(menu);

  mainWindow.on('closed', () => {
    mainWindow = null;
  });
}

app.on('window-all-closed', function () {
  if (process.platform !== 'darwin') app.quit();
});
