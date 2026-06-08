# GNUSlashLinux_Repo / repo.GNUSlashLinux

> 🕒 **Letztes automatisches Update:** 08.06.2026 um 18:55 Uhr

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
      <th align="center">Architecture</th>
      <th align="left">Status</th>
    </tr>
  </thead>
  <tbody>

<tr><td><b>gnuslashlinux-base</b></td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
<tr><td><b>gnuslashlinux-fonts</b></td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
<tr><td><b>gnuslashlinux-grub</b></td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
<tr><td><b>gnuslashlinux-plymouth</b></td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
<tr><td><b>gnuslashlinux-shellset</b></td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
<tr><td><b>gnuslashlinux-wallpapers</b></td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
<tr><td><b>gnuslashlinux-wallpapers-nord</b></td><td align='center'>all</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
<tr><td><b>xwayland-satellite</b></td><td align='center'>amd64</td><td><span style='color: #A3BE8C; font-weight: bold;'>[ active ]</span></td></tr>
  </tbody>
</table>

## 🚀 Einrichtung / Setup

Führe die folgenden Befehle im Terminal aus, um das Repository hinzuzufügen:
Run the following commands in your terminal to add the repository:

### 1. GPG-Schlüssel importieren / Import GPG keys
```bash
wget -O- https://github.io | gpg --dearmor | sudo tee /usr/share/keyrings/gnuslashlinux.gpg > /dev/null
```

### 2. Repository zur Paketquelle hinzufügen / Add repository to package source
```bash
echo "deb [signed-by=/usr/share/keyrings/gnuslashlinux.gpg] https://github.io trixie main" | sudo tee /etc/apt/sources.list.d/gnuslashlinux.list
```

### 3. Paketliste aktualisieren / Update package list
```bash
sudo apt update
```
