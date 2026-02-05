# Aikido DNS Lookup Application

This is a deliberately insecure PHP application created to demonstrate security scanning and AI-assisted remediation workflows with Aikido Security.

## What This App Does

A simple DNS lookup utility that:
- Accepts a domain name (e.g., `example.com`)
- Optionally accepts custom DNS servers (e.g., `8.8.8.8,1.1.1.1`)
- Queries DNS using the `dig` command
- Displays results in the browser

## Intentional Vulnerabilities

### 1. Command Injection (SAST - Critical)

- No input sanitization or validation
- Direct shell execution via backticks
- Can lead to arbitrary command execution, data exfiltration, backdoors

### 2. Hardcoded AWS Credentials (Secrets)

Fake AWS credentials hardcoded in source

**Why It's Dangerous:**
- Credentials committed to Git
- Anyone with repo access can read them
- Can lead to unauthorized AWS access


### Quick Start

```bash
cd aikido-dns-lookup-app
php -S localhost:8000
open http://localhost:8000
```