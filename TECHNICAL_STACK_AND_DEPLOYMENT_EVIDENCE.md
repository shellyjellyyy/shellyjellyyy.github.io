# GameAssetLab — Technical Architecture, Stack & Deployment Reference

This document provides all technical data, server configurations, DNS details, deployment logs, and architecture documentation required to complete **Sections 5, 7, and 8** of the **CSET489 Milestone I Report**.

---

## 1. Executive Summary & Site Overview

- **Project / Website Title**: GameAssetLab
- **Live URL**: `https://gameassetlab.me/`
- **GitHub Repository**: `https://github.com/shellyjellyyy/shellyjellyyy.github.io`
- **Niche**: 3D Game Asset Creation (Blender to Game Engines: Unity/Unreal)
- **Primary Target Audience**: Indie game developers, 3D student artists, and beginner 3D modelers looking for low-poly optimization workflows.

---

## 2. Infrastructure & Hosting Architecture (Section 8)

### 2.1 Server Specifications
- **Hosting Environment**: Cloud Virtual Private Server (VPS) / PaaS Container Infrastructure
- **Server Public IP**: `152.67.0.250`
- **Operating System**: Ubuntu 22.04 LTS (x86_64 Linux)
- **Deployment & Orchestration Engine**: Coolify (Self-hosted Developer PaaS) with Docker Compose Engine v2
- **Reverse Proxy / Ingress**: Traefik Edge Router (Coolify Managed)
- **Internal Web Server**: Nginx (Alpine Linux, mainline lightweight build)

### 2.2 Containerization & Docker Setup
The website is packaged and deployed using containerized micro-architecture:

1. **`Dockerfile`**:
   ```dockerfile
   FROM nginx:alpine
   COPY . /usr/share/nginx/html
   ```
   *Rationale*: Copies all static HTML, CSS, JavaScript, web fonts, and XML sitemaps directly into Nginx's document root at build time, ensuring zero runtime dependencies on host filesystem mounts.

2. **`docker-compose.yml`**:
   ```yaml
   services:
     web:
       build: .
       restart: unless-stopped
       ports:
         - "80"
       environment:
         - SERVICE_FQDN_WEB=https://gameassetlab.me
         - SERVICE_URL_WEB=https://gameassetlab.me
   ```

3. **Traffic Flow**:
   ```
   User Browser
       │
       ▼ (HTTPS : 443 with TLS 1.3)
   Cloudflare Edge Network (Proxy & CDN)
       │
       ▼ (HTTPS / Encrypted Origin Pull)
   VPS Ingress (152.67.0.250 : Traefik Reverse Proxy)
       │
       ▼ (Internal Docker Network : Port 80)
   Nginx Container (/usr/share/nginx/html)
   ```

---

## 3. Domain, DNS & Cloudflare Configuration (Section 7)

### 3.1 Domain Details
- **Domain Name**: `gameassetlab.me`
- **Top-Level Domain**: `.me`
- **Domain Registrar**: Namecheap
- **Nameservers**: Cloudflare (`*.ns.cloudflare.com`)
- **DNS Management**: Cloudflare Managed DNS

### 3.2 DNS Records Table (For Section 7.1)
| Record Type | Name / Host | Target / Value | TTL | Proxy Status | Purpose |
| :--- | :--- | :--- | :--- | :--- | :--- |
| **A** | `@` (apex) | `152.67.0.250` | Auto | Proxied (Orange Cloud) | Directs apex traffic to VPS |
| **CNAME** | `www` | `gameassetlab.me` | Auto | Proxied (Orange Cloud) | Canonical redirect to apex |

### 3.3 SSL/TLS & Edge Security
- **Cloudflare Encryption Mode**: **Full** (Ensures end-to-end encryption from visitor to Cloudflare Edge, and from Cloudflare to VPS origin).
- **Edge Certificate**: Universal SSL (ECDSA with automated auto-renewal).
- **Minimum TLS Version**: TLS 1.2 (TLS 1.3 enabled).
- **HTTPS Enforcement**: Always Use HTTPS enabled (Automatic 301 redirects for any incoming HTTP requests).

---

## 4. Website Structure & URL Hierarchy (Section 5)

The website uses a flat, SEO-optimized hierarchy designed for search crawlers:

```
https://gameassetlab.me/
├── index.html (Homepage / Hub)
├── about/ (About Us & Mission)
├── tutorials/ (Comprehensive Tutorials Index)
│   ├── low-poly-barrel-blender/ (Tutorial 1: 3D Modeling)
│   ├── blender-uv-unwrapping-game-asset/ (Tutorial 2: UV & Texturing)
│   └── blender-to-unity-fbx/ (Tutorial 3: Engine Export)
├── tutorial-category/
│   ├── low-poly-modeling/
│   ├── uv-texturing/
│   └── engine-export/
├── sitemap.xml (Root XML Index)
└── robots.txt (Crawler Directives)
```

### 4.1 Keyword-to-Page Mapping (For Section 5.2)
| Page Title | URL Slug | Target Primary Keyword | Search Intent | Word Count |
| :--- | :--- | :--- | :--- | :--- |
| **Homepage** | `/` | Blender game asset tutorials | Informational / Commercial | ~1,200 |
| **Tutorial 1** | `/tutorials/low-poly-barrel-blender/` | Low poly barrel blender | Informational / Educational | ~1,850 |
| **Tutorial 2** | `/tutorials/blender-uv-unwrapping-game-asset/` | Blender UV unwrapping game asset | Informational / Educational | ~2,100 |
| **Tutorial 3** | `/tutorials/blender-to-unity-fbx/` | Blender to Unity FBX export | Informational / Technical | ~1,650 |
| **About Us** | `/about/` | Game asset modeling workflow | Navigational / Informational | ~750 |

---

## 5. Technical Issues Faced & Fixes Applied (Section 8.2)

For **Section 8.2 (Issues Faced and Fixes)** of the report, use these real, documented engineering problems:

| # | Issue Encountered | Root Cause | Fix Applied | Result |
| :- | :--- | :--- | :--- | :--- |
| **1** | `Bind for 0.0.0.0:8080 failed: port is already allocated` | Host port 8080 was hardcoded in Docker Compose, conflicting with existing host processes on the VPS. | Removed host port binding and exposed container port `80` internally via Docker network. | Coolify Traefik proxy routes ingress traffic without port collisions. |
| **2** | Default "Welcome to nginx!" displayed instead of website. | Relative host bind volume (`.:/usr/share/nginx/html`) mounted an empty directory inside Coolify's build container. | Built a custom Docker image using `Dockerfile` with `COPY . /usr/share/nginx/html` and `build: .` in compose. | Website HTML, CSS, and media assets were baked directly into the image. |
| **3** | Mixed Content / Insecure HTTP Warnings | Cloudflare proxy forwarded client HTTPS requests, but origin container initially responded over plain HTTP headers. | Set Cloudflare SSL to Full mode and configured `SERVICE_FQDN_WEB=https://gameassetlab.me` in Coolify. | Clean padlock, automatic HTTPS redirects, zero mixed-content errors. |
| **4** | Outdated External Blender Documentation Links | External documentation URLs in tutorial Markdown templates referenced outdated Blender 2.8 manual pages. | Ran systematic regex audit and updated all references to modern Blender 4.2 LTS documentation. | Zero dead links (verified with HTTP 200 response codes). |

---

## 6. Report Completion Checklist for Your Team

Send this checklist to whoever is compiling the Word document (`Milestone_I_Report_Template.docx`):

- [x] **Section 5.1 & 5.2**: Site hierarchy and keyword mapping tables (copy directly from Section 4 of this doc).
- [x] **Section 5.3**: State that all live tutorial links have been audited and verified working.
- [x] **Section 7.1**: Fill DNS table with apex `152.67.0.250` and `www` CNAME (copy from Section 3.2).
- [x] **Section 7.2**: Insert Cloudflare dashboard screenshots (DNS, SSL/TLS Full mode).
- [x] **Section 8.0**: Fill VPS spec table (Ubuntu 22.04 LTS, Docker Compose, Nginx Alpine, Traefik).
- [x] **Section 8.1**: Outline deployment steps (GitHub Repo -> Coolify Git Webhook -> Docker Build -> Traefik Reverse Proxy).
- [x] **Section 8.2**: Fill the "Issues Faced and Fixes" table (copy directly from Section 5 of this doc).
- [x] **Section 8.3**: Insert live website screenshots (Homepage, Tutorial, SSL padlock).
