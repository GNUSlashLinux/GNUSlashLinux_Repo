# GNUSlashLinux_Repo / repo.GNUSlashLinux

> 🕒 **Letztes automatisches Update:** 08.06.2026 um 19:09 Uhr

## 🎚️ admin@gnuslashlinux:~ $ apt-repo --info

Willkommen im offiziellen GNUSlashLinux Online-Repository.
Du kannst diese Pakete direkt über Deinen APT-Paketmanager installieren.
<br><br>
Welcome to the official GNUSlashLinux online repository.
You can install these packages directly from your APT package manager.

## 📦 Verfügbare Pakete / Available Packages

<table>
  <thead>
    <tr>
      <th align="left">Package Name</th>
      <th align="center">Version</th>
      <th align="center">Architecture</th>
      <th align="left">Status</th>
    </tr>
  </thead>
  <tbody>

<tr><td><b>gnuslashlinux-base</b></td><td align='center'>1.0.0</td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
<tr><td><b>gnuslashlinux-fonts</b></td><td align='center'>1.0.0</td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
<tr><td><b>gnuslashlinux-grub</b></td><td align='center'>1.0.0</td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
<tr><td><b>gnuslashlinux-plymouth</b></td><td align='center'>1.0.0</td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
<tr><td><b>gnuslashlinux-shellset</b></td><td align='center'>1.0.0</td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
<tr><td><b>gnuslashlinux-wallpapers</b></td><td align='center'>1.0.0</td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
<tr><td><b>gnuslashlinux-wallpapers-nord</b></td><td align='center'>1.0.0</td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
<tr><td><b>xwayland-satellite</b></td><td align='center'>0.8.1-1</td><td align='center'>amd64</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
  </tbody>
</table>

## 🚀 Einrichtung / Setup

Führe die folgenden Befehle im Terminal aus, um das Repository hinzuzufügen:
Run the following commands in your terminal to add the repository:

### 1. GPG-Schlüssel importieren / Import GPG keys

<div style="position: relative; margin-bottom: 1em;">
  <pre><code id="code1">wget -O- https://github.io | gpg --dearmor | sudo tee /usr/share/keyrings/gnuslashlinux.gpg > /dev/null</code></pre>
  <button onclick="navigator.clipboard.writeText(document.getElementById('code1').innerText); this.textContent='Kopiert!';" style="position: absolute; top: 5px; right: 5px; padding: 5px 10px; background: #4C566A; color: #ECEFF4; border: none; border-radius: 4px; cursor: pointer;">Kopieren</button>
</div>

### 2. Repository zur Paketquelle hinzufügen / Add repository to package source

<div style="position: relative; margin-bottom: 1em;">
  <pre><code id="code2">echo "deb [signed-by=/usr/share/keyrings/gnuslashlinux.gpg] https://github.io trixie main" | sudo tee /etc/apt/sources.list.d/gnuslashlinux.list</code></pre>
  <button onclick="navigator.clipboard.writeText(document.getElementById('code2').innerText); this.textContent='Kopiert!';" style="position: absolute; top: 5px; right: 5px; padding: 5px 10px; background: #4C566A; color: #ECEFF4; border: none; border-radius: 4px; cursor: pointer;">Kopieren</button>
</div>

### 3. Paketliste aktualisieren / Update package list

<div style="position: relative; margin-bottom: 1em;">
  <pre><code id="code3">sudo apt update</code></pre>
  <button onclick="navigator.clipboard.writeText(document.getElementById('code3').innerText); this.textContent='Kopiert!';" style="position: absolute; top: 5px; right: 5px; padding: 5px 10px; background: #4C566A; color: #ECEFF4; border: none; border-radius: 4px; cursor: pointer;">Kopieren</button>
</div>
