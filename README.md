# GNUSlashLinux_Repo / repo.GNUSlashLinux

> 🕒 **Letztes automatisches Update:** 13.06.2026 um 20:21 Uhr

## 🎚️ admin@gnuslashlinux:~ $ apt-repo --info

Willkommen im offiziellen GNUSlashLinux Online-Repository.
Du kannst diese Pakete direkt über Deinen APT-Paketmanager installieren.

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
<tr><td><b>gnuslashlinux-grub-minimal</b></td><td align='center'>1.0.0</td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
<tr><td><b>gnuslashlinux-plymouth-minimal</b></td><td align='center'>1.0.0</td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
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

<div style="background: #2e3440; padding: 16px 12px; border: 2px solid #A3BE8C; border-radius: 8px; margin-bottom: 1em;">
  <input type="text" value="wget -O- https://github.io | gpg --dearmor | sudo tee /usr/share/keyrings/gnuslashlinux.gpg > /dev/null" readonly onclick="this.select();" style="width: 100%; background: transparent; color: #d8dee9; border: none; font-family: monospace; font-size: 14px; line-height: 1.6; outline: none; cursor: text;" />
  <small style="color: #616e88; display: block; margin-top: 8px;">💡 Klicke in die Zeile, um den Befehl direkt zu markieren (Strg+C / Cmd+C)</small>
</div>

### 2. Repository zur Paketquelle hinzufügen / Add repository to package source

<div style="background: #2e3440; padding: 16px 12px; border: 2px solid #A3BE8C; border-radius: 8px; margin-bottom: 1em;">
  <input type="text" value="echo &quot;deb [signed-by=/usr/share/keyrings/gnuslashlinux.gpg] https://github.io trixie main&quot; | sudo tee /etc/apt/sources.list.d/gnuslashlinux.list" readonly onclick="this.select();" style="width: 100%; background: transparent; color: #d8dee9; border: none; font-family: monospace; font-size: 14px; line-height: 1.6; outline: none; cursor: text;" />
  <small style="color: #616e88; display: block; margin-top: 8px;">💡 Klicke in die Zeile, um den Befehl direkt zu markieren (Strg+C / Cmd+C)</small>
</div>

### 3. Paketliste aktualisieren / Update package list

<div style="background: #2e3440; padding: 16px 12px; border: 2px solid #A3BE8C; border-radius: 8px; margin-bottom: 1em;">
  <input type="text" value="sudo apt update" readonly onclick="this.select();" style="width: 100%; background: transparent; color: #d8dee9; border: none; font-family: monospace; font-size: 14px; line-height: 1.6; outline: none; cursor: text;" />
  <small style="color: #616e88; display: block; margin-top: 8px;">💡 Klicke in die Zeile, um den Befehl direkt zu markieren (Strg+C / Cmd+C)</small>
</div>
