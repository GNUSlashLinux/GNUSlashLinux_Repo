# GNUSlashLinux_Repo / repo.GNUSlashLinux
# admin@gnuslashlinux:~ $ apt-repo --info

Willkommen im offiziellen GNUSlashLinux Online-Repository.
Du kannst diese Pakete direkt über Deinen APT-Paketmanager installieren.

Welcome to the official GNUSlashLinux online repository.
You can install these packages directly from your APT package manager.

## 📦 Available Packages / Verfügbare Pakete


| Package Name | Architecture | Downloads |
| :--- | :---: | :--- |

| **gnuslashlinux-base** | all | ![gnuslashlinux-base](https://shields.io) |
| **gnuslashlinux-fonts** | all | ![gnuslashlinux-fonts](https://shields.io) |
| **gnuslashlinux-grub** | all | ![gnuslashlinux-grub](https://shields.io) |
| **gnuslashlinux-plymouth** | all | ![gnuslashlinux-plymouth](https://shields.io) |
| **gnuslashlinux-shellset** | all | ![gnuslashlinux-shellset](https://shields.io) |
| **gnuslashlinux-wallpapers** | all | ![gnuslashlinux-wallpapers](https://shields.io) |
| **gnuslashlinux-wallpapers-nord** | all | ![gnuslashlinux-wallpapers-nord](https://shields.io) |

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
