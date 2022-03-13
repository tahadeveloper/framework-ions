<?php

return [
    'site_name' => 'موقع التجارة الإلكترونية',
    'titles' => [
        'dashboard' => 'لوحة التحكم الرئيسية',
        'add' => 'إضافة عنصر',
        'edit' => 'تحديث البند',
        'show' => 'عرض البيانات',
        'view' => 'التفاصيل',
        'view_details' => 'تفاصيل العنصر',
        'dt' => [
            'search' => 'بحث',
            'created_at' => 'أنشئت في',
            'updated_at' => 'محدث في',
            'caption_tbl' => 'عرض النتائج في ',
            'export_report' => 'تصدير التقرير',
            'export_report_title' => 'تقرير ',
            'export_btn' => [
                'copy' => 'نسخ إلى الحافظة',
                'excel' => 'تصدير بتنسيق Excel',
                'csv' => 'تصدير كملف CSV',
                'pdf' => 'تصدير كملف PDF',
            ]
        ],
        'btn' => [
            'actions' => 'الإجراءات',
            'edit' => 'تعديل',
            'delete' => 'حذف',
            'details' => 'تفاصيل',
            'privileges' => 'الامتيازات',
            'create' => 'اضف جديد',
            'show' => 'عرض البيانات',
            'cancel' => 'إلغاء',
            'save' => 'حفظ التغييرات',
            'delete_selected' => 'حذف المحدد',
            'view' => 'عرض',
        ],
    ],
    'inputs' => [
        'image' => [
            'change_avatar' => 'تغيير الصورة الرمزية',
            'cancel_avatar' => 'إلغاء الصورة الرمزية',
            'remove_avatar' => 'إزالة الصورة الرمزية',
        ],
        'select' => [
            'choose' => 'حدد أحد الخيارات'
        ]
    ],
    'notify area' => [
        'title' => 'الإشعارات',
        'alert title' => 'تنبيهات',
        'updates title' => 'التحديثات',
        'no notifications' => 'لا توجد إخطارات'
    ],
    'validation' => [
        'min' => 'يجب أن تكون كلمة المرور أكثر من 6 أحرف'
    ],
    'messages_box' => [
        'error_alert' => [
            'head' => 'توجد مشاكل!'
        ],
        'delete_dialog_select' => [
            'non_selected' => [
                'text' => 'يجب اختيار العناصر التي تريد حذفها أولاً.',
                'confirm_btn' => 'موافق'
            ],
            'confirm' => [
                'text' => 'هل أنت متأكد أنك تريد حذف العناصر المحددة؟',
                'confirm_btn' => 'نعم ، احذف!',
                'cancel_btn' => 'لا ، إلغاء'
            ],
            'deleted' => [
                'title' => 'تم الحذف',
                'content' => 'لقد قمت بحذف جميع العناصر المحددة!.',
            ],
            'error' => [
                'title' => 'خطأ',
                'content' => 'لا يمكن لبعض العناصر حذفها أو أن الحذف لا يعمل بشكل صحيح.',
            ]
        ],
        'delete_dialog' => [
            'title' => 'هل أنت واثق؟',
            'content' => "لن تكون قادرًا على التراجع عن هذا!",
            'approve_btn' => "نعم ، احذفها!",
            'cancel_btn' => "إلغاء",
            'deleted' => [
                'title' => "تم الحذف!",
                'content' => "لقد تم حذف البند الخاص بك.",
                'content_error' => "لم يتم حذف العنصر الخاص بك ، حاول مرة أخرى."
            ]
        ],
        'finish_dialog' => [
            'success' => 'نجاح',
            'error' => 'خطأ',
        ],
        'csrf_message' => 'طلبك منتهي الصلاحية',
        'delete_error' => 'حدث خطأ عند الحذف',
        'inside_msg' => [
            'error' => [
                'fail' => 'لا يمكن إضافة عنصر جديد: '
            ],
            'success_message' => 'تمت إضافة العنصر بنجاح.',
            'success_edit_message' => 'تم تحديث العنصر بنجاح.',
        ]
    ],
    'login' => [
        'login_in' => 'سجّل الدخول إلى',
        'new_here' => 'هل أنت جديد؟',
        'create_new' => 'إنشاء حساب',
        'forget_password' => 'هل نسيت كلمة السر ؟',
        'form' => [
            'login' => 'البريد الإلكتروني / الجوال',
            'password' => 'كلمة المرور',
            'remember_me' => 'تذكرني',
            'sign_in_btn' => 'تسجيل الدخول',
        ],
        'error_logins' => [
            'wrong_data' => 'قمت بإدخال بيانات خاطئة.',
            'not_active' => 'الحساب الخاص بك غير نشط.',
            'wrong_credentials' => 'تم إدخال بيانات اعتماد خاطئة.',
        ]
    ],
    'forget' => [
        'forget_title' => 'هل نسيت كلمة السر ؟',
        'forget_note' => 'أدخل بريدك الإلكتروني لإعادة تعيين كلمة المرور الخاصة بك.',
        'form' => [
            'email' => 'عنوان البريد الإلكتروني',
            'submit_btn' => 'إرسال',
            'cancel_btn' => 'إلغاء',
        ],
        'error_forget' => [
            'wrong_code' => 'لا يمكن إنشاء رمز لإعادة تعيين الحساب.',
            'mail_fail' => 'لا يمكن إرسال البريد ، حاول مرة أخرى في وقت لاحق.'
        ]
    ],
    'reset' => [
        'reset_title' => 'إعداد كلمة المرور الجديدة',
        'reset_hint' => 'بالفعل قمت بإعادة تعيين كلمة المرور الخاصة بك؟',
        'login_link' => 'تسجيل الدخول هنا',
        'form' => [
            'password' => 'كلمة المرور',
            'password_hint' => 'استخدم 8 أحرف أو أكثر مع مزيج من الأحرف والأرقام & amp؛ حرف او رمز.',
            'confirm_password' => 'تأكيد كلمة المرور',
            'submit_btn' => 'إرسال'
        ],
        'error_reset' => [
            'fail_reset' => 'مشكلة في إعادة تعيين كلمة المرور ، حاول مرة أخرى.',
        ]
    ],
    'register' => [
        'title' => 'إنشاء حساب',
        'have_account' => 'هل لديك حساب؟',
        'login_link' => 'تسجيل الدخول هنا',
        'form' => [
            'first_name' => 'الاسم الأول',
            'last_name' => 'اسم العائلة',
            'email' => 'البريد الإلكتروني',
            'mobile' => 'الهاتف المحمول',
            'password' => 'كلمة المرور',
            'password_hint' => 'استخدم 6 أحرف أو أكثر مع مزيج من الأحرف والأرقام & amp؛ حرف او رمز.',
            'confirm_password' => 'تأكيد كلمة المرور',
            'submit_btn' => 'إرسال'
        ],
        'error_register' => [
            'fail_register' => 'لا يمكن التسجيل لأنه توجد مشكلة: '
        ]
    ],
    'master_data' => [
        'user_data' => [
            'my_profile' => 'صفحتي الشخصية',
            'logout' => 'تسجيل خروج',
            'language' => 'اللغة'
        ]
    ],
    'user' => [
        'image' => 'الصورة',
        'image_hint' => 'الأنواع المسموح بها: * .png و * .jpg و *. ملفات الصور .jpeg مقبولة',
        'general_title' => 'عام',
        'other_title' => 'أخرى',
        'name' => 'الاسم',
        'first_name' => 'الاسم الأول',
        'last_name' => 'اسم العائلة',
        'email' => 'البريد الإلكتروني',
        'mobile' => 'الهاتف المحمول',
        'mobile_2' => 'هاتف آخر',
        'role' => 'المجموعة',
        'password' => 'كلمة المرور',
        'confirm_password' => 'تأكيد كلمة المرور',
        'address' => 'عنوان',
        'notes' => 'ملاحظات',
        'status' => [
            'text' => 'حالة',
            'active' => 'نشط',
            'inactive' => 'غير نشط'
        ]],
    'role' => [
        'name' => 'اسم',
        'slug' => 'الاسم المميز',
        'name_ar' => 'اسم (ع)',
        'name_en' => 'اسم (En)',
        'permissions' => 'تصاريح المجموعة',
    ],
    'control' => [
        'name' => 'اسم',
        'slug' => 'الاسم المميز',
        'name_ar' => 'اسم (ع)',
        'name_en' => 'اسم (En)',
        'parent' => 'الوالد',
        'no_parent' => 'لا والد',
        'icon_hint' => 'اختر أيقونة:',
        'link' => 'الرابط',
        'icon' => 'إيقونة',
        'active_name' => 'الاسم النشط',
        'is_tag' => 'العنصر هو علامة',
        'status' => [
            'text' => 'الحالة',
            'active' => 'نشط',
            'inactive' => 'غير نشط'
        ],
        'sort_item' => [
            'success_title' => 'نجاح',
            'error_title' => 'خطأ',
            'success_content' => 'تم فرز العنصر بنجاح',
            'error_content' => 'لم يتم فرز العنصر',
        ]
        ,
        'actions' => [
            'head_title' => 'الخيارات الموجودة في العنصر',
            'add_action' => 'أضف إجراء',
            'delete_action' => 'حذف',
            'slug' => 'الاسم المميز',
            'name_ar' => 'اسم(ع)',
            'name_en' => 'اسم(en)',
            'status' => [
                'text' => 'الحالة',
                'access' => 'موافق',
                'deny' => 'رفض'
            ],
            'delete_dialog' => [
                'text' => 'هل أنت متأكد أنك تريد حذف الإجراء؟',
                'confirm' => 'حذف',
                'cancel' => 'إلغاء',
            ]
        ]
    ],
    'error' => [
        'home_btn' => 'اذهب إلى الصفحة الرئيسية',
        'error_title' => 'خطأ في الوصول إلى النظام',
        'main_msg' => 'حدث خطأ ما!',
        'access_deny' => 'ليس لديك حق الوصول إلى هذه الخيارات '
    ]
];
