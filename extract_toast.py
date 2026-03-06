import subprocess
import re

out = subprocess.check_output(['git', 'show', '36bec86:resources/views/home.blade.php']).decode('utf-8')

print("=== HTML ===")
html_matches = re.findall(r'<div class="toast-container position-fixed bottom-0 start-0 p-3" style="z-index: 1055;">.*?</div>\s*</div>\s*</div>', out, re.DOTALL)
if html_matches:
    print(html_matches[0])

print("=== SCRIPT ===")
script_matches = re.findall(r'<script>\s*document\.addEventListener\(\'DOMContentLoaded\', function\(\)\s*\{[^{]*const\s+toastEl\s+=.*?</script>', out, re.DOTALL)
if script_matches:
    print(script_matches[0])
else:
    # try broader match
    script_matches = re.findall(r'const\s+toastEl\s+=.*?\);', out, re.DOTALL)
    if script_matches:
        print(script_matches[0])
