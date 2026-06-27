**create_دفاتر.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}

// Include header and navigation
require_once '../includes/header.php';
require_once '../includes/navigation.php';

// Include form validation and submission script
require_once '../includes/form_validation.php';

// Form data
$data = [
    'name' => '',
    'address' => '',
    'phone' => '',
    'email' => '',
];

// Form validation
if (isset($_POST['submit'])) {
    $data = $_POST;
    $errors = validate_form($data);
    if (empty($errors)) {
        // AJAX submission
        $ajax_url = '../backend/دفاتر.php';
        $ajax_data = [
            'action' => 'create',
            'data' => $data,
        ];
        $ajax_response = send_ajax_request($ajax_url, $ajax_data);
        if ($ajax_response['success']) {
            header('Location: list_دفاتر.php');
            exit;
        } else {
            $errors = $ajax_response['errors'];
        }
    }
}

// Form fields
$fields = [
    [
        'label' => 'اسم الدفتر',
        'name' => 'name',
        'type' => 'text',
        'value' => $data['name'],
        'errors' => isset($errors['name']) ? $errors['name'] : '',
    ],
    [
        'label' => 'عنوان الدفتر',
        'name' => 'address',
        'type' => 'text',
        'value' => $data['address'],
        'errors' => isset($errors['address']) ? $errors['address'] : '',
    ],
    [
        'label' => 'رقم الهاتف',
        'name' => 'phone',
        'type' => 'text',
        'value' => $data['phone'],
        'errors' => isset($errors['phone']) ? $errors['phone'] : '',
    ],
    [
        'label' => 'البريد الإلكتروني',
        'name' => 'email',
        'type' => 'email',
        'value' => $data['email'],
        'errors' => isset($errors['email']) ? $errors['email'] : '',
    ],
];

// Form script
?>
<script>
    $(document).ready(function() {
        $('#create_form').submit(function(event) {
            event.preventDefault();
            var formData = $(this).serialize();
            $.ajax({
                type: 'POST',
                url: '../backend/دفاتر.php',
                data: formData,
                success: function(response) {
                    if (response.success) {
                        window.location.href = 'list_دفاتر.php';
                    } else {
                        var errors = response.errors;
                        $.each(errors, function(key, value) {
                            $('#' + key + '_error').text(value);
                        });
                    }
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

<!-- Form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-lg font-bold text-slate-900 mb-4">إضافة دفتر جديد</h2>
    <form id="create_form" method="post">
        <?php foreach ($fields as $field) : ?>
            <div class="mb-4">
                <label for="<?= $field['name'] ?>" class="block text-sm font-bold text-slate-900"><?= $field['label'] ?></label>
                <input type="<?= $field['type'] ?>" id="<?= $field['name'] ?>" name="<?= $field['name'] ?>" value="<?= $field['value'] ?>" class="block w-full p-2 text-sm text-gray-700 border-gray-300 rounded-lg <?= isset($field['errors']) ? 'border-red-500' : '' ?>">
                <?php if (isset($field['errors'])) : ?>
                    <div id="<?= $field['name'] ?>_error" class="text-red-500 text-sm mt-1"><?= $field['errors'] ?></div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
        <button type="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إضافة</button>
    </form>
</div>

<?php
// Include footer
require_once '../includes/footer.php';
?>


**form_validation.php**

<?php
function validate_form($data) {
    $errors = [];
    if (empty($data['name'])) {
        $errors['name'] = 'اسم الدفتر مطلوب';
    }
    if (empty($data['address'])) {
        $errors['address'] = 'عنوان الدفتر مطلوب';
    }
    if (empty($data['phone'])) {
        $errors['phone'] = 'رقم الهاتف مطلوب';
    }
    if (empty($data['email'])) {
        $errors['email'] = 'البريد الإلكتروني مطلوب';
    }
    return $errors;
}
?>


**backend/دفاتر.php**

<?php
// Database connection
require_once '../includes/db.php';

// Form data
$data = $_POST;

// Create new record
$query = "INSERT INTO دفاتر (name, address, phone, email) VALUES (:name, :address, :phone, :email)";
$stmt = $pdo->prepare($query);
$stmt->execute([
    'name' => $data['name'],
    'address' => $data['address'],
    'phone' => $data['phone'],
    'email' => $data['email'],
]);

// Response
$response = [
    'success' => true,
];
echo json_encode($response);
?>