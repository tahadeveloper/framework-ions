<?php

return [
    'site_name' => 'Ionzile',
    'titles' => [
        'dashboard' => 'Dashboard',
        'add' => 'Add item',
        'edit' => 'Update item',
        'show' => 'Show',
        'view' => 'Details',
        'view_details' => 'Item Details',
        'dt' => [
            'search' => 'Search',
            'created_at' => 'Created at',
            'updated_at' => 'Updated at',
        ],
        'btn' => [
            'actions' => 'Actions',
            'edit' => 'Edit',
            'delete' => 'Delete',
            'details' => 'Details',
            'privileges' => 'Privileges',
            'create' => 'Create',
            'show' => 'Show',
            'cancel' => 'Cancel',
            'save' => 'Save Changes',
            'delete_selected' => 'Delete Selected',
            'view' => 'View',
        ],


    ],
    'inputs' => [
        'image' => [
            'change_avatar' => 'Change avatar',
            'cancel_avatar' => 'Cancel avatar',
            'remove_avatar' => 'Remove avatar',
        ],
        'select' => [
            'choose' => 'Select an option'
        ]
    ],
    'validation' => [
        'min' => 'The password must be more than 6 characters long'
    ],
    'messages_box' => [
        'error_alert' => [
            'head' => 'There are problems !'
        ],
        'delete_dialog_select' => [
            'non_selected' => [
                'text' => 'Must choose items you want to delete first.',
                'confirm_btn' => 'Ok'
            ],
            'confirm' => [
                'text' => 'Are you sure you want to delete selected items?',
                'confirm_btn' => 'Yes, delete!',
                'cancel_btn' => 'No, cancel'
            ],
            'deleted' => [
                'title' => 'Deleted',
                'content' => 'You have deleted all selected items!.',
            ],
            'error' => [
                'title' => 'Error',
                'content' => 'Some Items can not delete or delete function not working.',
            ]
        ],
        'delete_dialog' => [
            'title' => 'Are you sure?',
            'content' => "You will not be able to revert this!",
            'approve_btn' => "Yes, delete it!",
            'cancel_btn' => "Cancel",
            'deleted' => [
                'title' => "Deleted!",
                'content' => "Your item has been deleted.",
                'content_error' => "Your item did not delete, try again."
            ]
        ],
        'finish_dialog' => [
            'success' => 'Success',
            'error' => 'Error',
        ],
        'csrf_message' => 'Wrong call request.',
        'inside_msg' => [
            'error' => [
                'fail' => 'Can not add new item: '
            ],
            'success_message' => 'Item added successfully.',
            'success_edit_message' => 'Item updated successfully.',
        ]
    ],
    'login' => [
        'login_in' => 'Sign in to',
        'new_here' => 'New Here?',
        'create_new' => 'Create an Account',
        'forget_password' => 'Forgot Password ?',
        'form' => [
            'login' => 'Email / Mobile',
            'password' => 'Password',
            'remember_me' => 'Remember me',
            'sign_in_btn' => 'Login',
        ],
        'error_logins' => [
            'wrong_data' => 'You enter wrong data.',
            'not_active' => 'Your account is not active.',
            'wrong_credentials' => 'Wrong credentials entered.',
        ]
    ],
    'forget' => [
        'forget_title' => 'Forgot Password ?',
        'forget_note' => 'Enter your email to reset your password.',
        'form' => [
            'email' => 'Email address',
            'submit_btn' => 'Submit',
            'cancel_btn' => 'Cancel',
        ],
        'error_forget' => [
            'wrong_code' => 'Can not create code to reset account.',
            'mail_fail' => 'Can not send mail, try again later.'
        ]
    ],
    'reset' => [
        'reset_title' => 'Setup New Password',
        'reset_hint' => 'Already have reset your password ?',
        'login_link' => 'Sign in here',
        'form' => [
            'password' => 'Password',
            'password_hint' => 'Use 8 or more characters with a mix of letters, numbers &amp; symbols.',
            'confirm_password' => 'Confirm Password',
            'submit_btn' => 'Submit'
        ],
        'error_reset' => [
            'fail_reset' => 'Problem with resetting password, try again.',
        ]
    ],
    'register' => [
        'title' => 'Create an Account',
        'have_account' => 'Already have an account?',
        'login_link' => 'Sign in here',
        'form' => [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'email' => 'Email',
            'mobile' => 'Mobile',
            'password' => 'Password',
            'password_hint' => 'Use 6 or more characters with a mix of letters, numbers &amp; symbols.',
            'confirm_password' => 'Confirm Password',
            'submit_btn' => 'Submit'
        ],
        'error_register' => [
            'fail_register' => 'Can not register cuz there are problem: '
        ]
    ],
    'master_data' => [
        'user_data' => [
            'my_profile' => 'My Profile',
            'logout' => 'Sign Out',
            'language' => 'Language'
        ]
    ],
    'user' => [
        'image' => 'Image',
        'image_hint' => 'Allowed types: *.png, *.jpg and *.jpeg image files are accepted',
        'general_title' => 'General',
        'other_title' => 'Other',
        'name' => 'Name',
        'first_name' => 'First name',
        'last_name' => 'Last name',
        'email' => 'Email',
        'mobile' => 'Mobile',
        'mobile_2' => 'Other Mobile',
        'role' => 'Role',
        'password' => 'Password',
        'confirm_password' => 'Confirm Password',
        'address' => 'Address',
        'notes' => 'Notes',
        'status' => [
            'text' => 'Status',
            'active' => 'Active',
            'inactive' => 'Inactive'
        ]],
    'role' => [
        'name' => 'Name',
        'slug' => 'Slug',
        'name_ar' => 'Name (ع)',
        'name_en' => 'Name (En)',
        'permissions' => 'Role Permissions',
    ],
    'control' => [
        'name' => 'Name',
        'slug' => 'Slug',
        'name_ar' => 'Name (ع)',
        'name_en' => 'Name (En)',
        'parent' => 'Parent',
        'no_parent' => 'No Parent',
        'icon_hint' => 'Choose icon:',
        'link' => 'Link',
        'icon' => 'Icon',
        'active_name' => 'Active name',
        'status' => [
            'text' => 'Status',
            'active' => 'Active',
            'inactive' => 'Inactive'
        ],
        'sort_item' => [
            'success_title' => 'Success',
            'error_title' => 'Error',
            'success_content' => 'Item Ordered successfully',
            'error_content' => 'Item did not Ordered',
        ]
        ,
        'actions' => [
            'head_title' => 'Options in control',
            'add_action' => 'Add Action',
            'delete_action' => 'Delete',
            'slug' => 'Slug',
            'name_ar' => 'Name(ع)',
            'name_en' => 'Name(en)',
            'status' => [
                'text' => 'Status',
                'access' => 'Access',
                'deny' => 'Deny'
            ],
            'delete_dialog' => [
                'text' => 'Are you sure, You want to delete action control?',
                'confirm' => 'Delete',
                'cancel' => 'Cancel',
            ]
        ]
    ],
    'error' => [
        'home_btn' => 'Go to homepage',
        'error_title' => 'System Access Error',
        'main_msg' => 'Something went wrong!',
        'access_deny' => 'You do not have access to these option '
    ]
];
