# FWDP Base Repository Template

This repository serves as the **standard starting point** for all new
repositories under the Filipino Web Development Peers (FWDP) GitHub organization.

It ensures that every project starts with:
- Consistent structure
- Clear documentation
- Security and contribution guidelines
- CI-ready configuration

---

## 📌 When to Use This Template

Use this template when:
- Creating a **new FWDP repository**
- Starting a **community or official project**
- Bootstrapping a **student-friendly codebase**

Do NOT use this template to:
- Retroactively modify existing repositories
- Replace repository-specific workflows

---

## 🚀 How to Create a New Repository Using This Template

1. Go to the **FWDP GitHub organization**
2. Click **New repository**
3. In the **“Repository template”** dropdown, select: fwdp-repo-template
4. Enter the new repository name and description
5. Click **Create repository**

That’s it. The new repository will be created with the FWDP standard structure.

---

## 🛠 What You Must Update After Creating a Repo

Immediately update the following files:

### 1. `README.md`
- Replace the project name
- Describe the purpose of the repository
- Add setup or usage instructions

### 2. `docs/architecture.md`
- Describe the system design
- List the tech stack
- Explain major components

### 3. `.gitignore`
- Add stack-specific ignores if needed

### 4. CI Workflow (`.github/workflows/ci.yml`)
- Replace placeholder steps with real build/test steps

---

## 🔒 What You Should NOT Change

Do **NOT** remove or bypass:
- `CONTRIBUTING.md`
- `CODE_OF_CONDUCT.md`
- `SECURITY.md`
- Pull Request templates
- Issue templates

These enforce FWDP governance and community standards.

---

## 📂 Repository Tier & Governance

New repositories created from this template default to:
- **Tier 0 (Sandbox / Learning)**

Tier upgrades must follow the FWDP governance process and be approved
by Platform Maintainers.

See:
- FWDP Governance Repository
- `REPO-INVENTORY.md`

---

## 👥 Contribution Rules

All FWDP repositories follow organization-wide contribution rules.

Please review:
