<?php
/*
 * DNS Lookup Application - Main Entry Point
 *
 * PURPOSE: Intentionally vulnerable demo for security scanning and AI remediation
 *
 * VULNERABILITIES (intentional):
 * 1. Command Injection in dns_lookup.php (SAST)
 * 2. Hardcoded AWS credentials in config.php (Secrets)
 */

// Include the DNS lookup handler
require_once 'dns_lookup.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aikido DNS Lookup App</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Aikido DNS Lookup App</h1>
        <p>Query DNS nameservers for domain information</p>

        <!-- DNS Lookup Form -->
        <form method="GET" action="index.php">

            <!-- VULNERABLE: User input passed to backend without client-side validation -->
            <label for="domain">Domain Name</label>
            <input type="text" id="domain" name="domain" placeholder="example.com" required />
            <div class="helper">Enter the domain you want to look up</div>

            <label for="ns_dig">DNS Servers (optional)</label>
            <input type="text" id="ns_dig" name="ns_dig" placeholder="8.8.8.8,8.8.4.4" />
            <div class="helper">Comma-separated list of DNS servers. Default: 8.8.8.8,8.8.4.4</div>

            <button type="submit">Lookup DNS</button>
        </form>

        <?php if ($error): ?>
            <div class="results">
                <h3 style="color: #dc3545;">Error</h3>
                <!-- PROTECTED: htmlspecialchars escapes HTML/script to plain text, preventing XSS -->
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($results): ?>
            <div class="results">
                <h3>Results for: <?php echo htmlspecialchars($results['domain']); ?></h3>

                <h4>Query 1: <?php echo htmlspecialchars($results['dns_servers'][0]); ?></h4>
                <!-- VULNERABLE: Direct output without escaping, XSS risk -->
                <?php echo "<pre>{$results['first']}</pre>"; ?>

                <?php if ($results['second']): ?>
                    <h4>Query 2: <?php echo htmlspecialchars($results['dns_servers'][1]); ?></h4>
                    <!-- VULNERABLE: Direct output without escaping, XSS risk -->
                    <?php echo "<pre>{$results['second']}</pre>"; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </div>
</body>
</html>