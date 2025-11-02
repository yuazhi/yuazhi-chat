<?php
session_start();

// 检查是否已登录
if (!isset($_SESSION['user_id']) && !isset($_COOKIE['user_id'])) {
    // 如果既没有 session 也没有 cookie，重定向到登录页面
    header('Location: login.php');
    exit;
}

// 如果有 cookie 但没有 session，恢复 session
if (!isset($_SESSION['user_id']) && isset($_COOKIE['user_id'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['username'] = $_COOKIE['username'];
}
?>
<!DOCTYPE html>
<html lang="zh">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <!-- SEO Meta Tags -->
    <meta name="description" content="鸢栀AI助手是一款智能对话机器人，提供智能问答、图片创作、代码编程等多样化服务。作为新一代AI助手，我们致力于为用户提供更智能、更贴心的对话体验，让AI服务更加便捷、高效。">
    <meta name="keywords" content="鸢栀AI,AI助手,智能对话,AI绘画,AI编程,人工智能,聊天机器人,AI问答,智能助手,福瑞机器人，furry，furry Ai，兽圈机器人，虚拟福瑞">
    <meta name="author" content="鸢栀AI">
    <meta name="robots" content="index, follow">
    <!-- Open Graph Tags -->
    <meta property="og:title" content="鸢栀AI助手 - 智能对话新体验">
    <meta property="og:description" content="提供智能问答、图片创作、代码编程等多样化AI服务，打造更智能的对话体验">
    <meta property="og:image" content="/recommend.png">
    <meta property="og:url" content="https://chat.yuanzhi.ai/">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="zh_CN">
    <!-- Twitter Card Tags -->
    
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="鸢栀AI助手 - 智能对话新体验">
    <meta name="twitter:description" content="提供智能问答、图片创作、代码编程等多样化AI服务，打造更智能的对话体验">
    <meta name="twitter:image" content="/recommend.png">
    <title>聊天 - 与鸢栀对话</title>
    <link rel="stylesheet" href="/styles.css">
    <link rel="stylesheet" href="/index.css">
    <link rel="stylesheet" href="/atom-one-dark.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css">
    <script src="/highlight.min.js"></script>
    <script src="/marked.umd.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/contrib/auto-render.min.js"></script>
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#343541">
    <link rel="apple-touch-icon" href="/icons/icon-192x192.png">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <script src="api.js" type="module"></script>
    <script defer src="https://stat.rjjr.cn/random-string.js" data-website-id="d7715b9f-667b-4a6e-a011-31f076709162"></script>
    <script>LA.init({id:"3Kr8htmZBQXBnZTQ",ck:"3Kr8htmZBQXBnZTQ",screenRecord:true})</script>
    <script>
    console.log('%c萬事屋日記', 'font-size: 40px; color: #f5a8a4; font-weight: bold;');
    console.log('%c加入我們: yuazhi@rjjr.cn', 'font-size: 20px; color: #ffeb3b;');
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(registration => {
                        console.log('ServiceWorker registration successful');
                    })
                    .catch(err => {
                        console.log('ServiceWorker registration failed: ', err);
                    });
            });
        }

        // 检测是否在 PWA 应用中运行
        function isPWA() {
            const isIOSStandalone = window.navigator.standalone;
            const isOtherStandalone = window.matchMedia('(display-mode: standalone)').matches;
            return isIOSStandalone || isOtherStandalone || document.referrer.includes('android-app://');
        }

        // 检测是否为移动设备
        function isMobileDevice() {
            return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
        }

        // 更新 PWA 状态
        function updatePWAStatus() {
            console.log('Updating PWA status...'); // 添加调试日志
            if (isPWA() && isMobileDevice()) {
                console.log('Is PWA and mobile device'); // 添加调试日志
                document.body.classList.add('mobile-standalone');
                if (window.navigator.standalone) {
                    console.log('Is iOS standalone'); // 添加调试日志
                    document.body.classList.add('ios-standalone');
                }
            } else {
                document.body.classList.remove('mobile-standalone');
                document.body.classList.remove('ios-standalone');
            }
        }

        // 确保在 DOM 加载完成后执行
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', updatePWAStatus);
        } else {
            updatePWAStatus();
        }

        // 监听显示模式变化
        window.matchMedia('(display-mode: standalone)').addListener(updatePWAStatus);

        document.addEventListener('DOMContentLoaded', () => {
            let deferredPrompt;

            // 添加调试日志
            window.addEventListener('beforeinstallprompt', (e) => {
                console.log('beforeinstallprompt 触发');
                e.preventDefault();
                deferredPrompt = e;
                console.log('保存 deferredPrompt:', deferredPrompt);
                document.getElementById('pwaPrompt').classList.add('show');
            });

            // 获取安装按钮元素
            const installButton = document.getElementById('installPwa');
            console.log('安装按钮元素:', installButton); // 调试是否找到按钮

            // 绑定安装按钮点击事件
            installButton.addEventListener('click', async (e) => {
                console.log('点击安装按钮');
                e.preventDefault(); // 阻止默认行为
                console.log('deferredPrompt 状态:', deferredPrompt);
                
                try {
                    if (deferredPrompt !== null && deferredPrompt !== undefined) {
                        console.log('开始显示安装提示');
                        // 显示安装提示
                        await deferredPrompt.prompt();
                        console.log('提示框已显示');
                        
                        // 等待用户响应
                        const result = await deferredPrompt.userChoice;
                        console.log('用户选择结果:', result);
                        
                        // 清除保存的提示
                        deferredPrompt = null;
                        // 隐藏提示框
                        document.getElementById('pwaPrompt').classList.remove('show');
                    } else {
                        console.log('deferredPrompt 不存在');
                        // 如果在 iOS 设备上
                        if (/iPad|iPhone|iPod/.test(navigator.userAgent)) {
                            alert('在 iOS 设备上，请使用 Safari 浏览器的"添加到主屏幕"功能来安装此应用。');
                        } else {
                            alert('安装功能当前不可用。请确保使用支持 PWA 的现代浏览器。');
                        }
                    }
                } catch (error) {
                    console.error('安装过程出错:', error);
                    console.error('错误详情:', error.message);
                    alert('安装过程出现错误，请稍后重试。');
                }
            });

            // 监听安装成功事件
            window.addEventListener('appinstalled', (event) => {
                console.log('应用安装成功');
                document.getElementById('pwaPrompt').classList.remove('show');
                deferredPrompt = null;
            });

            // 关闭按钮事件
            const closeButton = document.getElementById('closePwaPrompt');
            closeButton.addEventListener('click', () => {
                console.log('关闭安装提示');
                document.getElementById('pwaPrompt').classList.remove('show');
            });
        });
    </script>
</head>
<body>
    <!-- 登录模态框 -->
    <div class="modal" id="loginModal">
        <div class="login-box">
            <h2>登录</h2>
            <form id="loginForm">
                <input type="text" id="username" placeholder="用户名" required>
                <input type="password" id="password" placeholder="密码" required>
                <button type="submit">登录</button>
            </form>
        </div>
    </div>

    <div class="pwa-install-prompt" id="pwaPrompt">
        <button class="close-prompt" id="closePwaPrompt">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <span>将应用安装到设备，更好的体验</span>
        <button id="installPwa">安装</button>
    </div>

    <div class="app-container">
        <!-- 侧边栏 -->
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>历史对话</h2>
                <div class="sidebar-buttons">
                    <button class="theme-toggle" id="themeToggle" title="切换主题">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="5"/>
                            <line x1="12" y1="1" x2="12" y2="3"/>
                            <line x1="12" y1="21" x2="12" y2="23"/>
                            <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                            <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                            <line x1="1" y1="12" x2="3" y2="12"/>
                            <line x1="21" y1="12" x2="23" y2="12"/>
                            <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                            <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                        </svg>
                    </button>
                    <button class="new-chat" title="新对话">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14m-7-7h14" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                    <button class="logout-button" title="退出登录" onclick="window.location.href='logout.php'">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M16 17l5-5-5-5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M21 12H9" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="chat-history" id="chatHistory">
                <!-- 天记录将在这里动态添加 -->
            </div>
        </aside>

        <!-- 主聊天区域 -->
        <main class="main-content">
            <header class="chat-header">
                <button class="menu-button" id="menuButton">☰</button>
                <div class="model-selector">
                    <div class="model-dropdown" id="modelDropdown">
                        <div class="model-option" data-model="yuanzhi">鸢栀助手</div>
                        <div class="model-option" data-model="gpt">通用助手</div>
                        <div class="model-option" data-model="draw">绘画助手</div>
                    </div>
                    <button class="model-button" id="modelButton">
                        <span class="model-name">鸢栀助手</span>
                        <span class="dropdown-arrow">▼</span>
                    </button>
                </div>
            </header>
       
            <div class="chat-container">
                <div class="chat-messages" id="main-chat-messages">
                    <!-- 消息将在这里动态添加 -->
                </div>

                <div class="input-container">
                    <textarea 
                        id="main-user-input" 
                        placeholder="输入消息..." 
                        rows="1"
                    ></textarea>
                    <button id="search-button" class="search-button" title="联网搜索">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="12" r="10"/>
                            <path d="M2 12h20"/>
                            <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/>
                        </svg>
                    </button>
                    <button id="send-button" class="send-button" title="发送">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M22 2L11 13" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M22 2L15 22L11 13L2 9L22 2Z" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>
                </div>
            </div>
        </main>
    </div>
    <script src="main.js" type="module"></script>
    <div class="modal-overlay" id="deleteConfirmModal" style="display: none;">
        <div class="modal-content">
            <h3>确认删除</h3>
            <p>确定要删除这个对话吗？</p>
            <div class="modal-buttons">
                <button class="modal-button cancel">取消</button>
                <button class="modal-button confirm">确定</button>
            </div>
        </div>
    </div>
</body>
</html> 
