<?php
// Fehlerberichterstattung aktivieren, um versteckte Abstürze sichtbar zu machen
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Basis-Konfiguration
$currentFolder = __DIR__;
$counterFile = __DIR__ . '/.counters.json';
$allowedThemes = ['nord', 'everforest', 'onedark', 'tokyonight', 'gruvbox'];
define('ADMIN_PASSWORD', 'Silent_4113n!'); // Hier Ihr Admin-Passwort ändern

// Theme- & Sprach-Auswahl aus Cookies oder Fallback
$currentTheme = $_COOKIE['theme'] ?? 'nord';
if (!in_array($currentTheme, $allowedThemes)) { $currentTheme = 'nord'; }
$currentLang = $_COOKIE['lang'] ?? 'de';
if (!in_array($currentLang, ['de', 'en'])) { $currentLang = 'de'; }

// Sprachpakete definieren - Teil A: Deutsch
$langData = [
    'de' => [
        'title' => 'repo.GNUSlashLinux', 'empty' => 'Keine Dateien im Verzeichnis gefunden.',
        'sort_by' => 'Sortieren nach:', 'name' => 'Name', 'size' => 'Größe', 'date' => 'Datum',
        'downloads' => 'Downloads:', 'download_btn' => 'Download', 'date_format' => 'd.m.Y H:i',
        'checksum' => 'Prüfsumme (MD5)', 'search_placeholder' => 'Dateien durchsuchen...',
        'admin_title' => 'Admin Dashboard', 'admin_login' => 'Admin Login', 'password' => 'Passwort',
        'login_btn' => 'Einloggen', 'logout_btn' => 'Abmelden', 'reset' => 'Zurücksetzen',
        'delete' => 'Löschen', 'file_password' => 'Datei-Passwort', 'save' => 'Speichern',
        'password_required' => 'Diese Datei ist geschützt. Bitte Passwort eingeben:', 'submit' => 'Absenden',
        'visibility' => 'Sichtbarkeit', 'show' => 'Sichtbar', 'hide' => 'Unsichtbar',
        'tag_label' => 'Kategorie / Tag', 'expiry_label' => 'Ablaufdatum (JJJJ-MM-TT)', 
        'expired' => 'Abgelaufen', 'last_download' => 'Letzter Download:', 'never' => 'Nie', 'all_tags' => 'Alle'
    ],
    'en' => [
        'title' => 'get.GNUSlashLinux', 'empty' => 'No files found in the directory.',
        'sort_by' => 'Sort by:', 'name' => 'Name', 'size' => 'Size', 'date' => 'Date',
        'downloads' => 'Downloads:', 'download_btn' => 'Download', 'date_format' => 'Y-m-d H:i',
        'checksum' => 'Checksum (MD5)', 'search_placeholder' => 'Search files...',
        'admin_title' => 'Admin Dashboard', 'admin_login' => 'Admin Login', 'password' => 'Password',
        'login_btn' => 'Login', 'logout_btn' => 'Logout', 'reset' => 'Reset',
        'delete' => 'Delete', 'file_password' => 'File Password', 'save' => 'Save',
        'password_required' => 'This file is protected. Please enter password:', 'submit' => 'Submit',
        'visibility' => 'Visibility', 'show' => 'Visible', 'hide' => 'Hidden',
        'tag_label' => 'Category / Tag', 'expiry_label' => 'Expiry Date (YYYY-MM-DD)', 
        'expired' => 'Expired', 'last_download' => 'Last Download:', 'never' => 'Never', 'all_tags' => 'All'
    ]
];
$t = $langData[$currentLang];

// Strukturierte Daten laden und abwärtskompatibel initialisieren
$countersData = file_exists($counterFile) ? json_decode(file_get_contents($counterFile), true) : [];
if (!isset($countersData['counts'])) { 
    $countersData = ['counts' => $countersData, 'passwords' => [], 'hidden' => [], 'tags' => [], 'expiry' => [], 'last_download' => [], 'md5' => []]; 
}
foreach (['passwords', 'hidden', 'tags', 'expiry', 'last_download', 'md5'] as $key) {
    if (!isset($countersData[$key])) { $countersData[$key] = []; }
}
// 1. Download-Logik mit Ablaufdatums- und Passwortprüfung
if (isset($_GET['download']) && !empty($_GET['download'])) {
    $filename = basename($_GET['download']); 
    $filePath = './' . $filename;
    
    if (!in_array(strtolower($filename), ['index.php', '.counters.json', 'readme.md', '.htaccess']) && file_exists($filePath) && is_file($filePath)) {
        
        $isHidden = !empty($countersData['hidden'][$filename]);
        $isExpired = false;
        if (!empty($countersData['expiry'][$filename])) {
            if (time() > strtotime($countersData['expiry'][$filename] . ' 23:59:59')) {
                $isExpired = true;
            }
        }

        // Zugriff verweigern, wenn unsichtbar oder abgelaufen und kein Admin eingeloggt
        if (($isHidden || $isExpired) && empty($_SESSION['is_admin'])) {
            die("Access denied.");
        }

        // Passwort-Prüfung für geschützte Dateien
        if (!empty($countersData['passwords'][$filename])) {
            if (($_POST['file_pwd'] ?? '') !== $countersData['passwords'][$filename]) {
                echo '<!DOCTYPE html><html data-theme="'.htmlspecialchars($currentTheme).'"><head><meta charset="UTF-8"><title>Protected</title><style>body{background:#2e3440;color:#eceff4;font-family:sans-serif;display:flex;justify-content:center;align-items:center;height:100vh;margin:0;}div{background:#3b4252;padding:2rem;border-radius:8px;border:1px solid #4c566a;text-align:center;}input,button{padding:0.5rem;margin:0.5rem;border-radius:4px;border:1px solid #4c566a;background:#2e3440;color:#eceff4;}</style></head><body><div><form method="POST"><h3>'.$t['password_required'].'</h3><input type="password" name="file_pwd" required><br><button type="submit">'.$t['submit'].'</button></form></div></body></html>';
                exit;
            }
        }

        // Statistik aktualisieren
        $countersData['counts'][$filename] = ($countersData['counts'][$filename] ?? 0) + 1;
        $countersData['last_download'][$filename] = time();
        file_put_contents($counterFile, json_encode($countersData));

        // Datei ausliefern
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath); exit;
    }
    die("Access denied or file not found.");
}
// Funktion für SVG-Icons (kompatibel mit allen PHP-Versionen)
function getFileIcon($filename) {
    $filenameLower = strtolower($filename);
    $ext = pathinfo($filenameLower, PATHINFO_EXTENSION);
    
    if (substr($filenameLower, -7) === '.tar.gz') {
        $ext = 'tar.gz';
    }
    
    $p = '<path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline>';
    switch ($ext) {
        case 'zip': case 'rar': case '7z': case 'tar': case 'gz': case 'tar.gz': 
            $p = '<polyline points="22 12 16 12 14 15 10 15 8 12 2 12"></polyline><path d="M5.45 5.11L2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"></path>'; break;
        case 'iso': 
            $p = '<circle cx="12" cy="12" r="10"></circle><circle cx="12" cy="12" r="3"></circle>'; break;
        case 'jpg': case 'jpeg': case 'png': case 'gif': case 'svg': case 'webp': 
            $p = '<rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline>'; break;
        case 'mp3': case 'wav': case 'ogg': case 'flac': 
            $p = '<path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle>'; break;
        case 'mp4': case 'mkv': case 'avi': case 'mov': 
            $p = '<polygon points="23 7 16 12 23 17 23 7"></polygon><rect x="1" y="5" width="15" height="14" rx="2" ry="2"></rect>'; break;
        case 'pdf': case 'txt': case 'md': 
            $p .= '<line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line>'; break;
        case 'js': case 'css': case 'html': case 'php': case 'py': case 'sh': 
            $p = '<polyline points="16 18 22 12 16 6"></polyline><polyline points="8 6 2 12 8 18"></polyline>'; break;
        case 'doc': case 'docx': case 'xls': case 'xlsx': case 'ppt': case 'pptx': 
            $p .= '<rect x="8" y="12" width="8" height="6" rx="1"></rect>'; break;
    }
    return '<svg viewBox="0 0 24 24" width="24" height="24" stroke="currentColor" stroke-width="2" fill="none" stroke-linecap="round" stroke-linejoin="round" class="icon">'.$p.'</svg>';
}

// Wandelt grundlegende Markdown-Syntax in valides HTML um
function parseMarkdown($txt) {
    $txt = htmlspecialchars($txt, ENT_QUOTES, 'UTF-8');
    $txt = preg_replace('/^### (.*?)$/m', '<h3>$1</h3>', $txt);
    $txt = preg_replace('/^## (.*?)$/m', '<h2>$1</h2>', $txt);
    $txt = preg_replace('/^# (.*?)$/m', '<h1>$1</h1>', $txt);
    $txt = preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $txt);
    $txt = preg_replace('/^(?:\*|-)\s+(.*?)$/m', '<li>$1</li>', $txt);
    $txt = preg_replace('/(<li>.*?<\/li>)/s', '<ul>$1</ul>', $txt);
    return nl2br(preg_replace('/<\/ul>\s*<ul>/', '', $txt));
}
// 2. Admin-Dashboard Authentifizierung & Aktionen (erweitert)
$isAdminMode = isset($_GET['admin']);
if (isset($_POST['admin_login']) && $_POST['admin_pwd'] === ADMIN_PASSWORD) { $_SESSION['is_admin'] = true; }
if (isset($_GET['logout'])) { unset($_SESSION['is_admin']); header('Location: index.php'); exit; }

if (!empty($_SESSION['is_admin'])) {
    if (isset($_GET['reset_count'])) { 
        $f = basename($_GET['reset_count']);
        $countersData['counts'][$f] = 0; 
        unset($countersData['last_download'][$f]);
    }
    if (isset($_GET['toggle_visibility'])) {
        $f = basename($_GET['toggle_visibility']);
        if (empty($countersData['hidden'][$f])) { $countersData['hidden'][$f] = true; } 
        else { unset($countersData['hidden'][$f]); }
    }
    if (isset($_GET['delete_file'])) {
        $f = basename($_GET['delete_file']);
        $filePath = './' . $f;
        if (!in_array(strtolower($f), ['index.php', '.counters.json', 'readme.md', '.htaccess']) && file_exists($filePath) && is_file($filePath)) {
            unlink($filePath); 
            unset($countersData['counts'][$f], $countersData['passwords'][$f], $countersData['hidden'][$f], $countersData['tags'][$f], $countersData['expiry'][$f], $countersData['last_download'][$f], $countersData['md5'][$f]);
        }
    }
    if (isset($_POST['save_file_pwd'])) {
        $f = basename($_POST['lock_file']);
        if (empty($_POST['lock_pwd'])) { unset($countersData['passwords'][$f]); } 
        else { $countersData['passwords'][$f] = $_POST['lock_pwd']; }
        if (empty($_POST['file_tag'])) { unset($countersData['tags'][$f]); } 
        else { $countersData['tags'][$f] = trim(htmlspecialchars($_POST['file_tag'])); }
        if (empty($_POST['file_expiry'])) { unset($countersData['expiry'][$f]); } 
        else { $countersData['expiry'][$f] = trim($_POST['file_expiry']); }
    }
    file_put_contents($counterFile, json_encode($countersData));
    if(isset($_GET['reset_count']) || isset($_GET['delete_file']) || isset($_POST['save_file_pwd']) || isset($_GET['toggle_visibility'])) { header('Location: index.php?admin'); exit; }
}

// Hilfsfunktion: Berechnet MD5 ressourcenschonend im 8KB-Stream (für Dateien bis 5GB+)
function stream_file_md5($path) {
    if (!file_exists($path) || !is_file($path)) return '-';
    $handle = fopen($path, "rb");
    if (!$handle) return '-';
    
    $ctx = hash_init('md5');
    while (!feof($handle)) {
        hash_update($ctx, fread($handle, 8192)); // Liest exakt 8 KB pro Schritt in den RAM
    }
    fclose($handle);
    return hash_final($ctx);
}

// 3. Failsafe Einlesen via GLOB & MD5-Caching
$filesData = [];
$allAvailableTags = [];
$rawFileList = glob("./*");
$dataChanged = false;

if (!isset($countersData['md5'])) { $countersData['md5'] = []; }

if (is_array($rawFileList)) {
    foreach ($rawFileList as $fullPath) {
        if (is_file($fullPath)) {
            $file = basename($fullPath);
            $fileLower = strtolower($file);
            
            if (in_array($fileLower, ['index.php', '.counters.json', 'readme.md', '.htaccess'])) {
                continue;
            }
            
            $isHidden = !empty($countersData['hidden'][$file]);
            $expiryDate = $countersData['expiry'][$file] ?? '';
            
            $isExpired = false;
            if (!empty($expiryDate)) {
                if (time() > strtotime($expiryDate . ' 23:59:59')) { $isExpired = true; }
            }
            
            if (($isHidden || $isExpired) && empty($_SESSION['is_admin'])) {
                continue;
            }

            $fileTag = $countersData['tags'][$file] ?? '';
            if (!empty($fileTag) && !in_array($fileTag, $allAvailableTags)) {
                $allAvailableTags[] = $fileTag;
            }

            // MD5-Caching Logik: Wenn der Hash fehlt, einmalig streamen und speichern
            if (empty($countersData['md5'][$file])) {
                $countersData['md5'][$file] = stream_file_md5($fullPath);
                $dataChanged = true;
            }
            $cachedMd5 = $countersData['md5'][$file];

            $filesData[] = [
                'name' => $file, 
                'size' => sprintf('%u', filesize($fullPath)), 
                'date' => filemtime($fullPath),
                'count' => $countersData['counts'][$file] ?? 0, 
                'md5' => $cachedMd5, 
                'password' => $countersData['passwords'][$file] ?? '', 
                'hidden' => $isHidden,
                'tag' => $fileTag, 
                'expiry' => $expiryDate, 
                'expired' => $isExpired,
                'last_download' => $countersData['last_download'][$file] ?? 0
            ];
        }
    }
}

if ($dataChanged) {
    file_put_contents($counterFile, json_encode($countersData));
}

// 4. Sortierung anwenden
$sortBy = $_GET['sort'] ?? 'name'; $sortOrder = $_GET['order'] ?? 'asc';
if (!empty($filesData)) {
    usort($filesData, function($a, $b) use ($sortBy, $sortOrder) {
        $r = ($sortBy === 'size' || $sortBy === 'date') ? ($a[$sortBy] <=> $b[$sortBy]) : strnatcasecmp($a['name'], $b['name']);
        return $sortOrder === 'desc' ? -$r : $r;
    });
}

function getSortUrl($type, $sB, $sO) { return "?sort=$type&order=".($sB===$type && $sO==='asc'?'desc':'asc').(isset($_GET['admin'])?'&admin':''); }
function getSortIndicator($type, $sB, $sO) { return $sB !== $type ? '' : ($sO === 'asc' ? ' ↑' : ' ↓'); }

$readmeContent = '';
$readmeFiles = glob("./[rR][eE][aA][dD][mM][eE].[mM][dD]");
if (!empty($readmeFiles) && is_array($readmeFiles) && is_file($readmeFiles[0])) {
    $readmeContent = parseMarkdown(file_get_contents($readmeFiles[0]));
}
?>
<!DOCTYPE html>
<html lang="<?php echo $currentLang; ?>" data-theme="<?php echo $currentTheme; ?>">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $t['title']; ?></title>
    <style>
        /* CSS Variablensystem für Themes */
        [data-theme="nord"] { --bg: #2e3440; --card: #3b4252; --text: #eceff4; --text-muted: #d8dee9; --accent: #88c0d0; --border: #4c566a; --danger: #bf616a; }
        [data-theme="everforest"] { --bg: #2b3339; --card: #323c41; --text: #d3c6aa; --text-muted: #9da9a0; --accent: #a7c080; --border: #3a454a; --danger: #e67e80; }
        [data-theme="onedark"] { --bg: #282c34; --card: #21252b; --text: #abb2bf; --text-muted: #5c6370; --accent: #61afef; --border: #3e4452; --danger: #e06c75; }
        [data-theme="tokyonight"] { --bg: #1a1b26; --card: #1f2335; --text: #a9b1d6; --text-muted: #565f89; --accent: #7aa2f7; --border: #24283b; --danger: #f7768e; }
        [data-theme="gruvbox"] { --bg: #282828; --card: #3c3836; --text: #fbf1c7; --text-muted: #a89984; --accent: #fabd2f; --border: #504945; --danger: #fb4934; }
        
        body { background-color: var(--bg); color: var(--text); font-family: sans-serif; margin: 0; padding: 2rem; }
        .container { max-width: 900px; margin: 0 auto; }
        header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--border); padding-bottom: 1rem; margin-bottom: 1.5rem; }
        .controls { display: flex; gap: 0.5rem; }
        select, input { background: var(--card); color: var(--text); border: 1px solid var(--border); padding: 0.5rem; border-radius: 4px; }
        .search-input { width: 100%; box-sizing: border-box; padding: 0.75rem; margin-bottom: 1rem; font-size: 1rem; }
        
        /* Tag-Filter-Leiste */
        .tag-filter-bar { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1.5rem; }
        .tag-btn { background: var(--card); color: var(--text-muted); border: 1px solid var(--border); padding: 0.4rem 0.8rem; border-radius: 20px; font-size: 0.85rem; cursor: pointer; font-weight: bold; }
        .tag-btn.active, .tag-btn:hover { background: var(--accent); color: var(--bg); border-color: var(--accent); }
        
        .sort-bar { display: flex; gap: 1rem; margin-bottom: 1rem; padding: 0.5rem; background: var(--card); border-radius: 6px; font-size: 0.9rem; border: 1px solid var(--border); }
        .sort-link { color: var(--text-muted); text-decoration: none; font-weight: bold; }
        .sort-link.active { color: var(--accent); }
        .file-list { list-style: none; padding: 0; margin: 0; }
        .file-item { background: var(--card); border: 1px solid var(--border); border-radius: 6px; padding: 1rem; margin-bottom: 0.75rem; display: flex; justify-content: space-between; align-items: center; }
        .file-item.is-hidden { opacity: 0.5; border-style: dashed; }
        .file-item.is-expired { border-color: var(--danger); }
        .file-left { display: flex; align-items: center; gap: 1rem; width: 100%; }
        .icon { color: var(--accent); }
        .file-info { display: flex; flex-direction: column; width: 100%; }
        .file-name { font-weight: bold; display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; }
        .file-meta { font-size: 0.85rem; color: var(--text-muted); margin: 0.25rem 0; display: flex; gap: 1rem; flex-wrap: wrap; }
        
        /* Badge-Styles für Kategorien und Ablaufstatus */
        .badge { font-size: 0.75rem; padding: 0.1rem 0.5rem; border-radius: 4px; font-weight: bold; background: var(--border); color: var(--text); }
        .badge.tag-badge { background: var(--accent); color: var(--bg); }
        .badge.danger-badge { background: var(--danger); color: var(--text); }
        
        .download-btn, .admin-btn { background: var(--accent); color: var(--bg); text-decoration: none; padding: 0.5rem 1rem; border-radius: 4px; font-weight: bold; cursor: pointer; border: none; }
        .admin-btn.danger { background: var(--danger); color: var(--text); }
        .md5-trigger { font-size: 0.85rem; color: var(--accent); cursor: pointer; text-decoration: underline; border: none; background: none; padding: 0; text-align: left; }
        .md5-text { font-family: monospace; font-size: 0.8rem; display: none; margin-top: 0.25rem; word-break: break-all; }
        .admin-actions { display: flex; gap: 0.5rem; margin-top: 0.75rem; align-items: center; flex-wrap: wrap; font-size: 0.85rem; border-top: 1px solid var(--border); padding-top: 0.5rem; }
        .admin-link { color: var(--text-muted); text-decoration: none; padding: 0.25rem; border: 1px solid var(--border); border-radius: 4px; font-size: 0.85rem; }
        .admin-form-block { display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap; margin-left: auto; }
        .admin-form-block input { padding: 0.25rem; font-size: 0.8rem; }
        .readme-box { margin-top: 3rem; background: var(--card); border: 1px solid var(--border); border-radius: 8px; padding: 1.5rem; }
    </style>
</head>
<body>
<!-- Modernes modales MD5-Popup -->
<div id="md5Modal" style="display:none; position:fixed; z-index:9999; left:0; top:0; width:100%; height:100%; background-color:rgba(0,0,0,0.6); align-items:center; justify-content:center;">
    <div style="background:var(--card); border:1px solid var(--border); padding:2rem; border-radius:8px; max-width:500px; width:90%; position:relative; box-shadow:0 4px 20px rgba(0,0,0,0.3); text-align:center;">
        <span onclick="closeMd5Modal()" style="position:absolute; top:0.5rem; right:1rem; color:var(--text-muted); font-size:1.5rem; cursor:pointer; font-weight:bold;">&times;</span>
        <h3 id="modalFileName" style="margin-top:0; color:var(--accent); word-break:break-all; font-size:1.1rem;"></h3>
        <p style="font-size:0.9rem; color:var(--text-muted); margin-bottom:0.5rem;"><?php echo $t['checksum']; ?>:</p>
        <div style="display:flex; gap:0.5rem; margin-top:1rem; justify-content:center; align-items:center;">
            <input type="text" id="modalMd5Value" readonly style="font-family:monospace; font-size:0.9rem; padding:0.5rem; background:var(--bg); border:1px solid var(--border); border-radius:4px; width:75%; text-align:center;">
            <button onclick="copyMd5ToClipboard()" class="admin-btn" style="padding:0.5rem; font-size:0.85rem;">📋</button>
        </div>
    </div>
</div>

<div class="container">
    <header>
        <h1><?php echo $isAdminMode ? $t['admin_title'] : $t['title']; ?></h1>
        <div class="controls">
            <a href="<?php echo !empty($_SESSION['is_admin']) ? '?logout' : ($isAdminMode ? 'index.php' : '?admin'); ?>" class="admin-link"><?php echo !empty($_SESSION['is_admin']) ? $t['logout_btn'] : ($isAdminMode ? 'Home' : 'Admin'); ?></a>
            <select onchange="document.cookie='lang='+this.value+';max-age=2592000;path=/';window.location.reload()">
                <option value="de" <?php echo $currentLang=='de'?'selected':''; ?>>DE</option>
                <option value="en" <?php echo $currentLang=='en'?'selected':''; ?>>EN</option>
            </select>
            <select onchange="document.documentElement.setAttribute('data-theme', this.value);document.cookie='theme='+this.value+';max-age=2592000;path=/';">
                <option value="nord" <?php echo $currentTheme=='nord'?'selected':''; ?>>Nord</option>
                <option value="everforest" <?php echo $currentTheme=='everforest'?'selected':''; ?>>Everforest</option>
                <option value="onedark" <?php echo $currentTheme=='onedark'?'selected':''; ?>>One Dark</option>
                <option value="tokyonight" <?php echo $currentTheme=='tokyonight'?'selected':''; ?>>Tokyo Night</option>
                <option value="gruvbox" <?php echo $currentTheme=='gruvbox'?'selected':''; ?>>Gruvbox</option>
            </select>
        </div>
    </header>

    <main>
        <?php if ($isAdminMode && empty($_SESSION['is_admin'])): ?>
            <div class="file-item" style="justify-content: center;"><form method="POST"><h3><?php echo $t['admin_login']; ?></h3><input type="password" name="admin_pwd" required><br><br><button type="submit" name="admin_login" class="admin-btn"><?php echo $t['login_btn']; ?></button></form></div>
        <?php else: ?>
            <!-- Live-Suchfeld -->
            <input type="text" id="sIn" class="search-input" placeholder="<?php echo $t['search_placeholder']; ?>" onkeyup="filterFiles()">
            
            <!-- Dynamische Kategorie-Filter -->
            <?php if (!empty($allAvailableTags)): ?>
                <div class="tag-filter-bar">
                    <button class="tag-btn active" onclick="filterTag('all', this)"><?php echo $t['all_tags']; ?></button>
                    <?php foreach ($allAvailableTags as $tag): ?>
                        <button class="tag-btn" onclick="filterTag('<?php echo htmlspecialchars($tag); ?>', this)"><?php echo htmlspecialchars($tag); ?></button>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <!-- Sortier-Menü -->
            <div class="sort-bar">
                <span><?php echo $t['sort_by']; ?></span>
                <a href="<?php echo getSortUrl('name', $sortBy, $sortOrder); ?>" class="sort-link <?php echo $sortBy==='name'?'active':''; ?>"><?php echo $t['name'].getSortIndicator('name', $sortBy, $sortOrder); ?></a>
                <a href="<?php echo getSortUrl('size', $sortBy, $sortOrder); ?>" class="sort-link <?php echo $sortBy==='size'?'active':''; ?>"><?php echo $t['size'].getSortIndicator('size', $sortBy, $sortOrder); ?></a>
                <a href="<?php echo getSortUrl('date', $sortBy, $sortOrder); ?>" class="sort-link <?php echo $sortBy==='date'?'active':''; ?>"><?php echo $t['date'].getSortIndicator('date', $sortBy, $sortOrder); ?></a>
            </div>

            <?php if (empty($filesData)): ?><p class="empty"><?php echo $t['empty']; ?></p><?php else: ?>
                <ul class="file-list" id="fList">
                    <?php foreach ($filesData as $i => $f): 
                        $sz = $f['size'] < 1048576 ? round($f['size']/1024, 1).' KB' : round($f['size'] / 1048576, 2).' MB';
                        $lastDlFormatted = $f['last_download'] ? date($t['date_format'], $f['last_download']) : $t['never'];
                    ?>
                        <li class="file-item <?php echo $f['hidden'] ? 'is-hidden' : ''; ?> <?php echo $f['expired'] ? 'is-expired' : ''; ?>" data-tag="<?php echo htmlspecialchars($f['tag']); ?>">
                            <div class="file-left">
                                <?php echo getFileIcon($f['name']); ?>
                                <div class="file-info">
                                    <span class="file-name">
                                        <?php if ($isAdminMode): echo htmlspecialchars($f['name']); else: ?><a href="?download=<?php echo urlencode($f['name']); ?>" style="color:inherit; text-decoration:none;"><?php echo htmlspecialchars($f['name']); ?></a><?php endif; ?>
                                        <?php if (!empty($f['password'])): ?><span class="badge danger-badge">🔒</span><?php endif; ?>
                                        <?php if ($f['hidden']): ?><span class="badge">👁️‍🗨️ <?php echo $t['hide']; ?></span><?php endif; ?>
                                        <?php if ($f['expired']): ?><span class="badge danger-badge">⏳ <?php echo $t['expired']; ?></span><?php endif; ?>
                                        <?php if (!empty($f['tag'])): ?><span class="badge tag-badge"><?php echo htmlspecialchars($f['tag']); ?></span><?php endif; ?>
                                    </span>
                                    <div class="file-meta">
                                        <span><?php echo $t['size']; ?> <strong><?php echo $sz; ?></strong></span>
                                        <span><?php echo $t['date']; ?> <strong><?php echo date($t['date_format'], $f['date']); ?></strong></span>
                                        <span><?php echo $t['downloads']; ?> <strong><?php echo $f['count']; ?></strong></span>
                                        <span><?php echo $t['last_download']; ?> <strong><?php echo $lastDlFormatted; ?></strong></span>
                                    </div>
                                    <div class="md5-details">
                                        <button class="md5-trigger" onclick="openMd5Modal('<?php echo addslashes(htmlspecialchars($f['name'])); ?>', '<?php echo $f['md5']; ?>')"><?php echo $t['checksum']; ?></button>
                                    </div>
                                    
                                    <!-- Erweiterte Inline-Admin-Schaltflächen -->
                                    <?php if ($isAdminMode && !empty($_SESSION['is_admin'])): ?>
                                        <div class="admin-actions">
                                            <a href="?toggle_visibility=<?php echo urlencode($f['name']); ?>&admin" class="admin-link"><?php echo $f['hidden'] ? $t['show'] : $t['hide']; ?></a>
                                            <a href="?reset_count=<?php echo urlencode($f['name']); ?>&admin" class="admin-link"><?php echo $t['reset']; ?></a>
                                            <a href="?delete_file=<?php echo urlencode($f['name']); ?>&admin" class="admin-link" style="color:var(--danger);" onclick="return confirm('Löschen?');"><?php echo $t['delete']; ?></a>
                                            <form method="POST" class="admin-form-block">
                                                <input type="hidden" name="lock_file" value="<?php echo htmlspecialchars($f['name']); ?>">
                                                <input type="text" name="lock_pwd" placeholder="<?php echo $t['file_password']; ?>" value="<?php echo htmlspecialchars($f['password']); ?>" style="width:90px;">
                                                <input type="text" name="file_tag" placeholder="<?php echo $t['tag_label']; ?>" value="<?php echo htmlspecialchars($f['tag']); ?>" style="width:90px;">
                                                <input type="text" name="file_expiry" placeholder="YYYY-MM-DD" value="<?php echo htmlspecialchars($f['expiry']); ?>" style="width:85px;">
                                                <button type="submit" name="save_file_pwd" class="admin-btn" style="padding:0.25rem 0.5rem; font-size:0.8rem;"><?php echo $t['save']; ?></button>
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (!$isAdminMode): ?><a href="?download=<?php echo urlencode($f['name']); ?>" class="download-btn"><?php echo $t['download_btn']; ?></a><?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        <?php endif; ?>
        <?php if (!empty($readmeContent) && !$isAdminMode): ?><div class="readme-box"><h3>README.md</h3><div><?php echo $readmeContent; ?></div></div><?php endif; ?>
    </main>
</div>
<script>
var activeTagFilter = 'all';

function filterFiles() {
    var searchFilter = document.getElementById('sIn').value.toLowerCase();
    var ul = document.getElementById('fList');
    if (!ul) return;
    var items = ul.getElementsByClassName('file-item');
    
    for (var i = 0; i < items.length; i++) {
        var nameSpan = items[i].getElementsByClassName('file-name')[0];
        if (nameSpan) {
            var txt = nameSpan.textContent || nameSpan.innerText;
            var itemTag = items[i].getAttribute('data-tag') || '';
            
            var matchesSearch = txt.toLowerCase().indexOf(searchFilter) > -1;
            var matchesTag = (activeTagFilter === 'all' || itemTag === activeTagFilter);
            
            items[i].style.display = (matchesSearch && matchesTag) ? "" : "none";
        }
    }
}

function filterTag(tagName, buttonElement) {
    activeTagFilter = tagName;
    var buttons = document.getElementsByClassName('tag-btn');
    for (var i = 0; i < buttons.length; i++) { buttons[i].classList.remove('active'); }
    buttonElement.classList.add('active');
    filterFiles();
}

function openMd5Modal(fileName, md5Value) {
    document.getElementById('modalFileName').innerText = fileName;
    document.getElementById('modalMd5Value').value = md5Value;
    document.getElementById('md5Modal').style.display = 'flex';
}

function closeMd5Modal() {
    document.getElementById('md5Modal').style.display = 'none';
}

function copyMd5ToClipboard() {
    var copyText = document.getElementById('modalMd5Value');
    copyText.select();
    copyText.setSelectionRange(0, 99999);
    navigator.clipboard.writeText(copyText.value).then(function() {
        var btn = document.querySelector('#md5Modal button');
        var origText = btn.innerText;
        btn.innerText = '✓';
        setTimeout(function() { btn.innerText = origText; }, 1500);
    });
}

window.onclick = function(event) {
    var modal = document.getElementById('md5Modal');
    if (event.target == modal) { closeMd5Modal(); }
}
</script>
</body>
</html>
