# GNUSlashLinux_Repo / repo.GNUSlashLinux

Willkommen im offiziellen GNUSlashLinux Online-Repository.
Du kannst diese Pakete direkt über Deinen APT-Paketmanager installieren.

Welcome to the official GNUSlashLinux online repository.
You can install these packages directly from your APT package manager.

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
