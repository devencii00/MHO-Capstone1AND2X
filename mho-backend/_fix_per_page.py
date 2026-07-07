import re, os

base = r'c:\laragon\www\OpolMHO\mho-backend'

admin_files = [
    r'\resources\views\dashviews\admin\appointments_view.blade.php',
    r'\resources\views\dashviews\admin\verification_approvals.blade.php',
    r'\resources\views\dashviews\admin\medicines_management.blade.php',
    r'\resources\views\dashviews\admin\patient_records.blade.php',
    r'\resources\views\dashviews\admin\reports_analytics.blade.php',
    r'\resources\views\dashviews\admin\services_management.blade.php',
    r'\resources\views\dashviews\admin\staff_management.blade.php',
    r'\resources\views\dashviews\admin\medical_background_viewer.blade.php',
]

total_view = 0

for f in admin_files:
    fp = base + f
    with open(fp, 'r', encoding='utf-8') as fh:
        content = fh.read()
    
    def replace_per_page(m):
        val = m.group(1)
        if val == '1':
            return m.group(0)
        return 'per_page=15'
    
    new_content = re.sub(r'per_page=(\d+)', replace_per_page, content)
    
    changed = sum(1 for old, new in zip(re.findall(r'per_page=(\d+)', content), re.findall(r'per_page=(\d+)', new_content)) if old != new)
    total_view += changed
    
    with open(fp, 'w', encoding='utf-8') as fh:
        fh.write(new_content)
    
    print(f'{os.path.basename(fp)}: {changed} per_page values changed')

# Backend controllers
# PatientController
fp = base + r'\app\Http\Controllers\PatientController.php'
with open(fp, 'r', encoding='utf-8') as fh:
    content = fh.read()
content = content.replace('?? 50)', '?? 15)')
content = re.sub(r"'max' => 100", "'max' => 15", content)
content = re.sub(r"'max'=>100", "'max'=>15", content)
with open(fp, 'w', encoding='utf-8') as fh:
    fh.write(content)
print('PatientController.php: updated')

# MessagingController
fp = base + r'\app\Http\Controllers\MessagingController.php'
with open(fp, 'r', encoding='utf-8') as fh:
    content = fh.read()
content = content.replace("per_page', 20)", "per_page', 15)")
content = content.replace("per_page', 50)", "per_page', 15)")
with open(fp, 'w', encoding='utf-8') as fh:
    fh.write(content)
print('MessagingController.php: updated')

# DoctorScheduleController
fp = base + r'\app\Http\Controllers\DoctorScheduleController.php'
with open(fp, 'r', encoding='utf-8') as fh:
    content = fh.read()
content = content.replace("per_page', 50)", "per_page', 15)")
content = re.sub(r"'max' => 100", "'max' => 15", content)
with open(fp, 'w', encoding='utf-8') as fh:
    fh.write(content)
print('DoctorScheduleController.php: updated')

# ServiceController
fp = base + r'\app\Http\Controllers\ServiceController.php'
with open(fp, 'r', encoding='utf-8') as fh:
    content = fh.read()
content = re.sub(r"'max' => 100", "'max' => 15", content)
with open(fp, 'w', encoding='utf-8') as fh:
    fh.write(content)
print('ServiceController.php: updated')

# TransactionController
fp = base + r'\app\Http\Controllers\TransactionController.php'
with open(fp, 'r', encoding='utf-8') as fh:
    content = fh.read()
content = re.sub(r"'max' => 100", "'max' => 15", content)
with open(fp, 'w', encoding='utf-8') as fh:
    fh.write(content)
print('TransactionController.php: updated')

print(f'\nTotal view changes: {total_view}')
