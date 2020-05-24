function changeviews(e, view) {

    $('.viewby').removeClass('active');
    $(e).addClass('active');
    if (view == 'list') {
        $('.div-table').removeClass('grid-view');
        $('#employees_details').attr('data-view', 'list');
    } else {
        $('.div-table').addClass('grid-view');
        $('#employees_details').attr('data-view', 'grid');
    }
}

if (uri_page == 'employees') {

    var page = 1;
    var employee_id = $('#employee_id').val();
    var username = $('#username').val();
    var department_id = $('#department_id').val();
    var employee_email = $('#employee_email').val();

    initalloading_employee(page, employee_id, username, employee_email, department_id);
}

function changedesignation(designation, userid) {

    $.post(base_url + 'employees/changedesignation', { designation: designation, userid: userid }, function (datas) {
        if (datas) {
            filter_next_page(1);
            $('.message_notifcation').html('<p class="alert alert-success">Designation updated successfully...</div>');
            setTimeout(function () {
                $('.message_notifcation').html('');
            }, 5000);
        }
    });
}

function filter_next_page(page = 1) {

    var employee_id = $('#employee_id').val();
    var username = $('#username').val();
    var department_id = $('#department_id').val();
    var employee_email = $('#employee_email').val();

    initalloading_employee(page, employee_id, username, employee_email, department_id);
}

function initalloading_employee(page, employee_id, username, employee_email, department_id) {

    var viewby = $('#employees_details').attr('data-view');

    $.post(base_url + 'employees/employees_list', {
        page: page,
        employee_id: employee_id,
        username: username,
        employee_email: employee_email,
        department_id: department_id
    }, function (datas) {

        var htmlbody = '';
        datas = JSON.parse(datas);
        var records = datas.list;
        var current_page = datas.current_page;
        var total_page = datas.total_page;
        var recordscount = records.length;

        if (recordscount > 0) {
            for (var i = 0; i < recordscount; i++) {

                var record = records[i];
                var fullname = record.fullname;
                var designations = record.designations;
                var user_status;
                if(record.activated == 1)
                {
                    user_status = 'Active';
                }else{
                    user_status = 'InActive';
                }

                htmlbody += '<div class="div-row" style="width:100%;">' +
                    '<div class="div-cell user-cell">' +
                    '<div class="user_det_list">' +
                    '<a href="'+base_url+'employees/profile_view/'+record.id+'"><span class="avatar">' + fullname.charAt(0).toUpperCase() + '</span>' +
                    '<h2><span class="username-info">' + fullname.toUpperCase() + '</span></a>' +
                    ' <span class="userrole-info">' + record.designation + '</span></h2>' +
                    '</div>' +
                    '</div>' +
                    '<div class="div-cell user-identity">' +
                    '<p>' + record.department + '</p>' +
                    '</div>' +
                    '<div class="div-cell user-identity">' +
                    '<p>FT-00' + record.id + '</p>' +
                    '</div>' +
                    '<div class="div-cell user-mail-info">' +
                    '<p>' + record.email + '</p>' +
                    '</div>' +
                    '<div class="div-cell number-info">' +
                    '<p>' + record.phone + '</p>' +
                    '</div>' +
                    '<div class="div-cell create-date-info">' +
                    '<p>' + record.doj + '</p>' +
                    '</div>' +
                    '<div class="div-cell user-role-info">' +
                    '<div class="dropdown">';

                if (designations.length > 0) {
                    htmlbody += '<a class="btn btn-white btn-sm rounded dropdown-toggle" data-toggle="dropdown" aria-expanded="false">' + record.designation + ' <i class="caret"></i></a>' +
                        '<ul class="dropdown-menu">';
                    $.each(designations, function (index, value) {
                        htmlbody += '<li><a href="javascript:void(0)" onclick="changedesignation(' + value.id + ',' + record.id + ')">' + value.designation + '</a></li>';
                    });


                    htmlbody += '</ul>';
                }
                htmlbody += '</div>' +
                    '</div>' +
                    '<div class="div-cell create-date-info">' +
                    '<p>' + user_status + '</p>' +
                    '</div>' +
                    '<div class="div-cell user-action-info">' +
                    '<div class="text-right">' +
                    '<div class="dropdown">' +
                    '<a href="#" class="action-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-ellipsis-v"></i></a>' +
                    '<ul class="dropdown-menu pull-right">' +
                    '<li><a href="' + base_url + 'employees/profile_view/' + record.id + '"   title="Employee"><i class="fa fa-pencil m-r-5"></i>Edit</li>' +
                    '<li><a href="' + base_url + 'employees/delete/' + record.id + '"  data-toggle="ajaxModal"><i class="fa fa-trash-o m-r-5"></i>Delete</a></li>';
                    if(record.activated == 2 ){
                        htmlbody += '<li><a href="' + base_url + 'employees/change_inactive/'+record.id+ '" ><i class="fa fa-eye m-r-5"></i>Active</a></li>';
                    }else{
                        htmlbody += '<li><a href="' + base_url + 'employees/change_inactive/'+record.id+ '" ><i class="fa fa-eye-slash m-r-5"></i>InActive</a></li>';
                    }
                    htmlbody += '</ul>' +
                    '</div>' +
                    '</div>' +
                    '</div>' +
                    '</div>';
            }
        } else {
            htmlbody = '<div class="row filter-row"><div class="col-md-12">No records found.</div></div>';
        }
        var html = '<div class="div-heading">' +
            '<div class="div-cell">' +
            '<p>Name</p>' +
            '</div>' +
            '<div class="div-cell">' +
            '<p>Department</p>' +
            '</div>' +
            '<div class="div-cell">' +
            '<p>Employee ID</p>' +
            '</div>' +
            '<div class="div-cell">' +
            '<p>Email</p>' +
            '</div>' +
            '<div class="div-cell">' +
            '<p>Mobile</p>' +
            '</div>' +
            '<div class="div-cell">' +
            '<p>Join Date</p>' +
            '</div>' +
            '<div class="div-cell">' +
            '<p>Role</p>' +
            '</div>' +
            '<div class="div-cell">' +
            '<p>Status</p>' +
            '</div>' +
            '<div class="div-cell text-right">' +
            '<p>Action</p>' +
            '</div>' +
            '</div>';
        var classname = '';
        var classpage = '';
        var k = 1;
        if (viewby == 'grid') {
            classname = 'grid-view';
        }

        var pagination_html = '';
        total_page = parseInt(total_page);

        if (total_page > 1) {

            pagination_html = '<div class="row row-paginate">' +
                '<div class="col-sm-12">' +
                '<div class="dataTables_paginate paging_simple_numbers" id="table-projects_paginate">' +
                '<ul class="pagination">';

            total_page = parseInt(total_page);

            for (var k = 1; k <= total_page; k++) {
                if (current_page == k) {
                    classpage = 'active';
                } else { classpage = ''; }
                pagination_html += '<li class="paginate_button ' + classpage + '"><a href="javascript:void(0)" onclick="filter_next_page(' + k + ')">' + k + '</a></li>';
            }
            pagination_html += '</ul></div></div></div>';
        }
        var final_html = '<div class="table-responsive"><div class="div-table ' + classname + '">' + html + htmlbody + '</div></div>' + pagination_html ;

        $('#employees_details').html(final_html);

    });

}


if (uri_page == 'attendance') {
    var page = 1;
    var employee_id = $('#employee_id').val();
    var employee_name = $('#employee_name').val();
    var attendance_month = $('#attendance_month').val();
    var attendance_year = $('#attendance_year').val();
    load_attendance_details(employee_name, attendance_month, attendance_year, page, employee_id);
}



function attendance_next_filter_page(page = 1) {
    var employee_name = $('#employee_name').val();
    var employee_id = $('#employee_id').val();
    var attendance_month = $('#attendance_month').val();
    var attendance_year = $('#attendance_year').val();
    load_attendance_details(employee_name, attendance_month, attendance_year, page, employee_id);

}

function load_attendance_details(employee_name, attendance_month, attendance_year, page, employee_id) {
    $.post(base_url + 'attendance/attendance_list', {
        page: page,
        employee_id: employee_id,
        employee_name: employee_name,
        attendance_month: attendance_month,
        attendance_year: attendance_year
    }, function (datas) {

        var attendance_footer = '';
        var attendance_body = '';
        var attendance_head = '';

        datas = JSON.parse(datas);
        var last_day = datas.last_day;
        var current_page = datas.current_page;
        var total_page = datas.total_page;
        var attendance_list = datas.attendance_list;
        var recordscount = attendance_list.length;
        attendance_head = '<div class="table-responsive"><table class="table table-striped custom-table m-b-0"><thead><tr><th>Employee</th>';
        for (var ik = 1; ik <= last_day; ik++) {
            attendance_head += '<th>' + ik + '</th>';
        }
        attendance_head += '</tr></thead>';
        attendance_body += '<tbody>';
        if (recordscount > 0) {
            for (var i = 0; i < recordscount; i++) {

                var record = attendance_list[i];

                var name = record.fullname;
                var attendance = record.attendance;

                attendance_body += '<tr><td><a href="'+base_url + 'attendance/details/'+record.user_id+'">' + name + '</a></td>';

               // console.log(attendance);

                var j=1;
                $.each(attendance, function (key, rec) {
                   // console.log(j);
                    var status = rec.day;
                    var punchin = rec.punch_in;
                    var punch_out = rec.punch_out;
                    if(punch_out != ''){
                        var start = moment.duration(punchin, "HH:mm");
                        var end = moment.duration(punch_out, "HH:mm");
                        var diff = end.subtract(start);
                        var hr =  diff.hours(); // return hours
                        var mins = diff.minutes(); // return minutes   
                    }
                    attendance_body += '<td >';

                    if (status == '0') {
                        if(punchin == '' && punch_out == ''){
                            attendance_body += '<i class="fa fa-close text-danger"></i>';
                        }
                    } else if (status == '1') {
                        if((punchin != ''  && punch_out != '')){
                            attendance_body += '<a href="'+base_url + 'attendance/attendance_details/'+record.user_id+'/'+j+'/'+attendance_month+'/'+attendance_year+'" data-toggle="ajaxModal" ><i class="fa fa-check text-success"></i></a>';
                        }else{
                            attendance_body += '<div class="half-day">'
                                                    +'<span class="first-off"><a href="'+base_url + 'attendance/attendance_details/'+record.user_id+'/'+j+'/'+attendance_month+'/'+attendance_year+'" data-toggle="ajaxModal" ><i class="fa fa-check text-success"></i></a></span>' 
                                                    +'<span class="first-off"><i class="fa fa-close text-danger"></i></span>'
                                                +'</div>';
                        }
                    } else if (status == '2') {
                        attendance_body += '<i class="text-success" data-toggle="tooltip" title="Worked Hours"></i>';
                    } else if (status == '0') {
                        attendance_body += '<i class="fa fa-exclamation-triangle text-danger" data-toggle="tooltip" title="No Record for Punch in"></i>';
                    } else if (status == '') {
                        attendance_body += '-';
                    }
                    attendance_body += '</td>';

                    ++j;

                });
                attendance_body += '</tr>';
            }
        } else {
            attendance_body += '<tr><td></td></tr>';
        }
        attendance_body += '</tbody>';

        attendance_body += '</table></div>';

        total_page = parseInt(total_page);

        if (total_page > 1) {

            attendance_footer = '<div class="row"><div class="col-sm-12">' +
                '' +
                '<div class="dataTables_paginate paging_simple_numbers" id="table-projects_paginate">' +
                '<ul class="pagination m-r-15">';

            total_page = parseInt(total_page);

            for (var k = 1; k <= total_page; k++) {
                if (current_page == k) {
                    classpage = 'active';
                } else { classpage = ''; }
                attendance_footer += '<li class="paginate_button ' + classpage + '"><a href="javascript:void(0)" onclick="attendance_next_filter_page(' + k + ')">' + k + '</a></li>';
            }
            attendance_footer += '</ul></div></div></div>';
        }

        attendance_footer += '<div class="row"><div class="col-md-12"><div class="pagination"></div></div></div>';
        var attendance_html = attendance_head + attendance_body + attendance_footer;
        $('#attendance_table').html(attendance_html);

    });
}


$(document).ready(function(){

    $('#checkuser_email').change(function(){
        // alert($(this).val());
        var check_email = $(this).val();
        // alert(isEmail(check_email));
        if(isEmail(check_email))
        {
            $('#error_emailid').css('display','none');
            $('#register_btn').removeAttr('disabled');
            $.post(base_url+'employees/check_user_email/',{user_email:check_email},function(res){
                if(res == 'yes'){
                    $('#already_email').css('display','');
                    $('#register_btn').attr('disabled','disabled');
                }else{
                    $('#already_email').css('display','none');
                    $('#register_btn').removeAttr('disabled');

                }
            });
        }else{
            $('#error_emailid').css('display','');
            // alert('hi');
            $('#register_btn').attr('disabled','disabled');
        }
    });

    function isEmail(email) {
      var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
      return regex.test(email);
    }


    $('#check_username').change(function(){
        var check_username = $(this).val();
        $.post(base_url+'employees/check_username/',{check_username:check_username},function(res){
                if(res == 'yes'){
                    $('#already_username').css('display','');
                    $('#register_btn').attr('disabled','disabled');
                }else{
                    $('#already_username').css('display','none');
                    $('#register_btn').removeAttr('disabled');

                }
        });
    });


    /* Clients Module validations  */

    $('#client_search').click(function(){
        var clientname = $('#client_name').val();
        var client_email = $('#client_email').val();
        if(clientname == '' && client_email == '')
        {
            $('#client_name').focus();
            $('#client_name').css('border-color','#77A7DB');
            $('#client_email').css('border-color','#77A7DB');
            $('#client_name_error').removeClass('display-none').addClass('display-block');
            $('#client_email_error').removeClass('display-none').addClass('display-block');
            return false;
        }
        else
        {
            $('#client_name').css('border-color','#ccc');
            $('#client_email').css('border-color','#ccc');
            $('#client_name_error').removeClass('display-block').addClass('display-none');
            $('#client_email_error').removeClass('display-block').addClass('display-none');
        }
    });


    $(document).on('click',"#nextCreateGeneral",function() {
       if($('#create_company_name').val() != '' && $('#create_company_notes').val() != '' &&  /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test($('#create_company_email').val()))
            $('#tabsClient a[href="#tab-client-contact"]').tab('show')
        else
        {
            console.log('general submit');
            $("#createClient").trigger( "click" );
        }
    });

    $(document).on('click',"#nextCreateContact",function() {
        if($('#create_company_name').val().trim() != '' && $('#create_company_notes').val().trim() != '' &&  /^(\+\d{1,3}[- ]?)?\d{10}$/.test($('#create_company_mobile').val()) && /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test($('#create_company_email').val()))
            $('#tabsClient a[href="#tab-client-web"]').tab('show')
        else
        {
            console.log('contact submit');
            $("#createClient").trigger( "click" );
        }
    });

    $(document).on('click','#createClient',function(){
        clientValidateCreate();
    });


    /* Create Form Client  */

    function clientValidateCreate()
    {
        function isPhonePresent() {
            console.log($('#create_client_phone').val().length > 0);
            return $('#create_client_phone').val().length > 0;
        }

        $.validator.addMethod("mobilevalidation",
        function(value, element) {
                return /^(\+\d{1,3}[- ]?)?\d{10}$/.test(value);
        },
        "Please enter a valid mobile number."
        );
        
        $.validator.addMethod("phonevalidation",
            function(value, element) {
                return /^(\+\d{1}[- ]?)?\d{6,14}[0-9]$/.test(value);
                    //return /^\+(?:[0-9] ?){6,14}[0-9]$/.test(value);
            },
        "Please enter a valid phone number."
        );

        $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
        "Please enter a valid email address."
        );

        $("#createClientForm").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                company_name: {
                    required: true
                },
                notes: {
                    required: true
                },
                company_email: {
                    required: true,
                    emailvalidation: 'emailvalidation'
                },
                company_mobile: {
                    required: true,
                    minlength: 10,
                    maxlength:12,
                    mobilevalidation: 'mobilevalidation'
                },
                company_phone: {
                    minlength: {
                        param:7,
                        depends: isPhonePresent
                    },
                    maxlength: {
                        param:15,
                        depends: isPhonePresent
                    },
                    phonevalidation: {
                        param:'phonevalidation',
                        depends: isPhonePresent
                    },
                }
            },
            messages: {
                company_name: {
                    required: "Company Name must not be empty"
                },
                notes: {
                    required: "Please fill notes field"
                },
                company_email: {
                    required: "Email Id is required",
                    emailvalidation: "Please enter a valid email Id"
                },
                company_mobile: {
                    required: "Mobile Number is required",
                    minlength: "Minimum Length Should be 10 digit",
                    maxlength: "Maximum Length Should be 12 digit",
                    mobilevalidation: "Number should be Valid Mobile Number"
                },
                company_phone: {
                    minlength: "Minimum Length Should be 7 digit",
                    maxlength: "Maximum Length Should be 15 digit",
                    phonevalidation: "Entered Number is Invalid"
                }
            },
            invalidHandler: function(e, validator){
                console.log(validator);
                if(validator.errorList.length)
                $('#tabsClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
           });
    }

    $(document).on('click',"#nextEditGeneral",function() {
        if($('#edit_company_name').val() != '' && $('#edit_company_notes').val() != '' &&  /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test($('#edit_company_email').val()))
             $('#tabsUptClient a[href="#tab-client-contact"]').tab('show')
         else
         {
             console.log('general submit');
             $("#updateClient").trigger( "click" );
         }
     });
 
     $(document).on('click',"#nextEditContact",function() {
         if($('#edit_company_name').val().trim() != '' && $('#edit_company_notes').val().trim() != '' &&  /^(\+\d{1,3}[- ]?)?\d{10}$/.test($('#edit_company_mobile').val()) && /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test($('#edit_company_email').val()))
             $('#tabsUptClient a[href="#tab-client-web"]').tab('show')
         else
         {
             console.log('contact submit');
             $("#updateClient").trigger( "click" );
         }
     });

     /* Update Form Client  */
 
     $(document).on('click','#updateClient',function(){

        function isUptPhonePresent() {
            console.log($('#edit_client_phone').val().length > 0);
            return $('#edit_client_phone').val().length > 0;
        }

        $.validator.addMethod("mobilevalidation",
        function(value, element) {
                return /^(\+\d{1,3}[- ]?)?\d{10}$/.test(value);
        },
        "Please enter a valid phone number."
        );
        
        $.validator.addMethod("phonevalidation",
            function(value, element) {
                return /^(\+\d{1}[- ]?)?\d{6,14}[0-9]$/.test(value);
                    //return /^\+(?:[0-9] ?){6,14}[0-9]$/.test(value);
            },
        "Please enter a valid phone number."
        );

        $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
        "Please enter a valid email address."
        );

        $("#editClientForm").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                company_name: {
                    required: true
                },
                notes: {
                    required: true
                },
                company_email: {
                    required: true,
                    emailvalidation: 'emailvalidation'
                },
                company_mobile: {
                    required: true,
                    minlength: 10,
                    maxlength:12,
                    mobilevalidation: 'mobilevalidation'
                },
                company_phone: {
                    minlength: {
                        param:7,
                        depends: isUptPhonePresent
                    },
                    maxlength: {
                        param:15,
                        depends: isUptPhonePresent
                    },
                    phonevalidation: {
                        param:'phonevalidation',
                        depends: isUptPhonePresent
                    },
                }
            },
            messages: {
                company_name: {
                    required: "Company Name must not be empty"
                },
                notes: {
                    required: "Please fill notes field"
                },
                company_email: {
                    required: "Email Id is required",
                    emailvalidation: "Please enter a valid email Id"
                },
                company_mobile: {
                    required: "Mobile Number is required",
                    minlength: "Minimum Length Should be 10 digit",
                    maxlength: "Maximum Length Should be 12 digit",
                    mobilevalidation: "Number should be Valid Mobile Number"
                },
                company_phone: {
                    minlength: "Minimum Length Should be 7 digit",
                    maxlength: "Maximum Length Should be 15 digit",
                    phonevalidation: "Entered Number is Invalid"
                }
            },
            invalidHandler: function(e, validator){
                if(validator.errorList.length)
                $('#tabsUptClient a[href="#' + jQuery(validator.errorList[0].element).closest(".tab-pane").attr('id') + '"]').tab('show')
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
        });


        /* Add Contact in Client Module */


        $(document).on('click','#addContactClient',function(){

        $.validator.addMethod("mobilevalidation",
        function(value, element) {
                return /^(\+\d{1,3}[- ]?)?\d{10}$/.test(value);
        },
        "Please enter a valid phone number."
        );
        
        $.validator.addMethod("phonevalidation",
            function(value, element) {
                return /^(\+\d{1}[- ]?)?\d{6,14}[0-9]$/.test(value);
                    //return /^\+(?:[0-9] ?){6,14}[0-9]$/.test(value);
            },
        "Please enter a valid phone number."
        );

        $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
        "Please enter a valid email address."
        );

            $("#addContactForm").validate({
                ignore: [],
                rules: {
                    fullname: {
                        required: true
                    },
                    username: {
                        required: true
                    },
                    email: {
                       required: true,
                       emailvalidation: 'emailvalidation'
                    },
                    password: {
                        required: true,
                        minlength : 6
                    },
                    confirm_password: {
                        required: true,
                        minlength : 6,
					    equalTo : "#password"
                    },
                    phone: {
                        required: true,
                        minlength: 7,
                        maxlength:15,
                        phonevalidation: 'phonevalidation'
                    }
                },
                messages: {
                    fullname: {
                        required: "Full Name must not be empty"
                    },
                    username: {
                        required: "User Name must not be empty"
                    },
                    email: {
                        required: "Email Id is required",
                        emailvalidation: "Please enter a valid email Id"
                    },
                    password: {
                        required : "Password field must not be empty",
                        minlength : "Minimum Length Should be 5 letters"
                    },
                    confirm_password: {
                        required : "Confirm Password field must not be empty",
                        minlength : "Maximum Length Should be 5 letters",
					    equalTo : "Password and Confirm Password are Mismatched"
                    },
                    phone: {
                        required: "Phone Number is required",
                        minlength: "Minimum Length Should be 7 digit",
                        maxlength: "Maximum Length Should be 15 digit",
                        phonevalidation: "Entered Number is Invalid"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
            });

        /* Update Contact in Client Module */

        function isUptContactMobilePresent() {
            console.log($('#edit_contact_mobile').val().length > 0);
            return $('#edit_contact_mobile').val().length > 0;
        }

        $(document).on('click','#updateContactClient',function(){

        $.validator.addMethod("mobilevalidation",
        function(value, element) {
                return /^(\+\d{1,3}[- ]?)?\d{10}$/.test(value);
        },
        "Please enter a valid phone number."
        );
        
        $.validator.addMethod("phonevalidation",
            function(value, element) {
                return /^(\+\d{1}[- ]?)?\d{6,14}[0-9]$/.test(value);
                    //return /^\+(?:[0-9] ?){6,14}[0-9]$/.test(value);
            },
        "Please enter a valid phone number."
        );

        $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
        "Please enter a valid email address."
        );

            $("#editContactForm").validate({
                ignore: [],
                rules: {
                    fullname: {
                        required: true
                    },
                    email: {
                       required: true,
                       emailvalidation: 'emailvalidation'
                    },
                    phone: {
                        required: true,
                        minlength: 7,
                        maxlength:15,
                        phonevalidation: 'phonevalidation'
                    },
                    mobile: {
                        minlength: {
                            param:7,
                            depends: isUptContactMobilePresent
                        },
                        maxlength: {
                            param:15,
                            depends: isUptContactMobilePresent
                        },
                        phonevalidation: {
                            param:'mobilevalidation',
                            depends: isUptContactMobilePresent
                        },
                    }
                },
                messages: {
                    fullname: {
                        required: "Full Name must not be empty"
                    },
                    email: {
                        required: "Email Id is required",
                        emailvalidation: "Please enter a valid email Id"
                    },
                    phone: {
                        required: "Phone Number is required",
                        minlength: "Minimum Length Should be 7 digit",
                        maxlength: "Maximum Length Should be 15 digit",
                        phonevalidation: "Entered Number is Invalid"
                    },
                    mobile: {
                        minlength: "Minimum Length Should be 10 digit",
                        maxlength: "Maximum Length Should be 12 digit",
                        phonevalidation: "Entered Number is Invalid"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
            });


        /* Upload Files in Client Module */

        
        $(document).on('click','#client_file_upload',function(){

            $("#clientFilesForm").validate({
                ignore: [],
                rules: {
                    title: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    'clientfiles[]': { 
                        required: true, 
                        extension: "png|jpe?g|gif|doc|docx|pdf|txt|xls", 
                        filesize: 3133456
                    }
                },
                messages: {
                    title: {
                        required: "Title must not be empty"
                    },
                    description: {
                        required: "Description must not be empty"
                    },
                    'clientfiles[]': {
                        required: "Please upload a file on empty fields",
                        extension: "Please upload image and text or doc files",
                        filesize: "One of your uploaded filesize must not exceed 3MB"
                    }
                },
                submitHandler: function(form) {
                    //form.submit();
                }
                
               });
            });


            /* Client Comment Form */

            $(document).on('click','#create_client_comment',function(){

                var textareaValue = $('.foeditor-client-comment').summernote('code');
                console.log(textareaValue)
                if(textareaValue == '<p><br></p>' || textareaValue == '')
                {
                   $('#client_comment_error').removeClass('display-none').addClass('display-block');
                   $('.note-editable').trigger('focus');
                   return false;
                }
                else
                {
                    $('#client_comment_error').removeClass('display-block').addClass('display-none');
                                 
                }
                
            });
            

            // $('#employee_search_btn').click(function(){
            //     var employee_id = $('#employee_id :selected').val();
            //     var ser_leave_date_from = $('#search_from_date').val();
            //     var ser_leave_date_to = $('#search_to_date').val();
            //     if(employee_id =='' && ser_leave_date_from =='' && ser_leave_date_to ==''){
                   
            //         return false;
            //     }
            // });


        /* Employees Module  */

        /* All Employees */

        $(document).on('click','#employee_search_btn',function(){
            var employee_id = $('#employee_id').val();
            var username = $('#username').val();
            var email = $('#employee_email').val();

            

            if(employee_id == '' && username == '' && email == ''){
                $('#employee_id').focus();
                $('#employee_id').css('border-color','#77A7DB');
                $('#username').css('border-color','#77A7DB');
                $('#employee_email').css('border-color','#77A7DB');
                $('#employee_id_error').removeClass('display-none').addClass('display-block');
                $('#employee_name_error').removeClass('display-none').addClass('display-block');
                $('#employee_email_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#employee_id').css('border-color','#ccc');
                $('#username').css('border-color','#ccc');
                $('#employee_email').css('border-color','#ccc');
                $('#employee_id_error').removeClass('display-block').addClass('display-none');
                $('#employee_name_error').removeClass('display-block').addClass('display-none');
                $('#employee_email_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        });

        
        $(document).on('click','#register_btn',function(){
            console.log('Add Employee');
            $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
            "Please enter a valid email address."
            );

            $.validator.addMethod("isPhoneValid",
                function(value, element) {
                        return /^[\s\+]?([\0-9]{2}\s?)?((\([0-9]{3}\))|[0-9]{3})[\s\-]?[\0-9]{3}[\s\-]?[0-9]{4}$/.test(value);
                },
            "Please enter a valid Phone number."
            );

            function isLeaderPresent() {
                console.log($('#is_teamlead').prop('checked'));
                return $('#is_teamlead').prop('checked') == true;
            }

            $("#employeeAddForm").validate({
                ignore: [],
                rules: {
                    fullname: {
                        required: true
                    },
                    username: {
                        required: true
                    },
                    email: {
                        required: true,
                        emailvalidation: 'emailvalidation'
                    },
                    password: {
                        required: true,
                        minlength : 6
                    },
                    confirm_password: {
                        required: true,
                        minlength : 6,
					    equalTo : "#password"
                    },
                    phone: {
                        required: true,
                        minlength: 10,
                        isPhoneValid: 'isPhoneValid'
                    },
                    department_name: {
                        required:true
                    },
                    designations: {
                        required:true
                    },
                    team_leaders_name: {
                        required:isLeaderPresent
                    },
                     emp_doj: {
                        required:true
                    }
                },
                messages: {
                    fullname: {
                        required: 'Fullname must not empty'
                    },
                    username: {
                        required: 'Username must not empty'
                    },
                    email: {
                        required: 'Email field is required',
                        emailvalidation: 'Entered email is invalid'
                    },
                    password: {
                        required: 'Password is required',
                        minlength : 'Password should be 6 characters minimum'
                    },
                    confirm_password: {
                        required: 'Confirm password is required',
                        minlength : 'Password should be 6 characters minimum',
					    equalTo : "Passwords are mismatched"
                    },
                    phone: {
                        required: 'Phone must not empty',
                        minlength: "Numbers minimum length should be 10 digits",
                        isPhoneValid: 'Entered Number is invalid'
                    },
                    department_name: {
                        required:'Please select a department',
                    },
                    designations: {
                        required:'Please select a designation'
                    },
                    team_leaders_name: {
                        required:'Please select a Team Leader'
                    },
                    emp_doj: {
                        required:'Please select a DOJ'
                    },
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });
        
        $(document).on('click','#employee_edit_user',function(){
            console.log('update Employee');
            
            $.validator.addMethod("mobilevalidation",
            function(value, element) {
                    return /^(\+\d{1,3}[- ]?)?\d{10}$/.test(value);
            },
            "Please enter a valid mobile number."
            );

            $.validator.addMethod("isPhoneValid",
                function(value, element) {
                        return /^[\s\+]?([\0-9]{2}\s?)?((\([0-9]{3}\))|[0-9]{3})[\s\-]?[\0-9]{3}[\s\-]?[0-9]{4}$/.test(value);
                },
            "Please enter a valid Phone number."
            );

            function isMobilePresent() {
                console.log($('#edit_employee_mobile').val().length);
                return $('#edit_employee_mobile').val().length > 0;
            }

            $("#employeeEditUser").validate({
                ignore: [],
                rules: {
                    fullname: {
                        required: true
                    },
                    phone: {
                        required: true,
                        minlength: 10,
                        isPhoneValid: 'isPhoneValid'
                    },
                    mobile: {
                        minlength: {
                            param:10,
                            depends: isMobilePresent
                        },
                        mobilevalidation: {
                            param:'mobilevalidation',
                            depends: isMobilePresent
                        }
                    },
                    department_id: {
                        required:true
                    },
                    designations: {
                        required:true
                    }
                },
                messages: {
                    fullname: {
                        required: 'Fullname must not empty'
                    },
                    mobile: {
                        minlength: 'Mobile number minimum length should be 10 digit',
                        mobilevalidation: 'Mobile Number is invalid'
                    },
                    phone: {
                        required: 'Phone must not empty',
                        minlength: "Phone number minimum length should be 10 digits",
                        isPhoneValid: 'Entered Number is invalid'
                    },
                    department_id: {
                        required:'Please select a department',
                    },
                    designations: {
                        required:'Please select a designation'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        /* Leaves submodule */

        $(document).on('click','#admin_search_leave',function(){
            var leaveType = $('#ser_leave_type option:selected').val();
            var status = $('#ser_leave_sts option:selected').val();
            var username = $('#ser_leave_user_name').val();
            var dateFrom = $('#ser_leave_date_from').val();
            var dateTo = $('#ser_leave_date_to').val();

            console.log(leaveType);
            console.log(status);
            console.log(username);
            console.log(dateFrom);
            console.log(dateTo);

            if(leaveType == '' && status == '' && username == '' && dateFrom == '' && dateTo == ''){
                $('#ser_leave_user_name').focus();
                $('#ser_leave_user_name').css('border-color','#77A7DB');
                $('#ser_leave_date_from').css('border-color','#77A7DB');
                $('#ser_leave_date_to').css('border-color','#77A7DB');
                $('#ser_leave_type_error').removeClass('display-none').addClass('display-block');
                $('#ser_leave_sts_error').removeClass('display-none').addClass('display-block');
                $('#ser_leave_user_name_error').removeClass('display-none').addClass('display-block');
                $('#ser_leave_date_from_error').removeClass('display-none').addClass('display-block');
                $('#ser_leave_date_to_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#ser_leave_sts').css('border-color','#ccc');
                $('#ser_leave_user_name').css('border-color','#ccc');
                $('#ser_leave_date_from').css('border-color','#ccc');
                $('#ser_leave_date_to').css('border-color','#ccc');
                $('#ser_leave_type_error').removeClass('display-block').addClass('display-none');
                $('#ser_leave_user_name_error').removeClass('display-block').addClass('display-none');
                $('#ser_leave_sts_error').removeClass('display-block').addClass('display-none');
                $('#ser_leave_date_from_error').removeClass('display-block').addClass('display-none');
                $('#ser_leave_date_to_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        });

        $(document).on('click','#employee_add_leave',function(){
            console.log('leave');
            $("#employeesAddLeave").validate({
                ignore: [],
                rules: {
                    req_leave_type: {
                        required: true
                    },
                    req_leave_date_from: {
                        required: true
                    },
                    req_leave_date_to: {
                        required: true
                    },
                    req_leave_reason: {
                        required: true
                    }
                    
                },
                messages: {
                    req_leave_type: {
                        required: 'Please select a leave type'
                    },
                    req_leave_date_from: {
                        required: 'From Date is required'
                    },
                    req_leave_date_to: {
                        required: 'To Date is required'
                    },
                    req_leave_reason: {
                        required: 'Leave reason is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        
        $(document).on('click','#employee_create_holiday',function(){
            console.log('leave');
            $("#employeeCreateHoliday").validate({
                ignore: [],
                rules: {
                    holiday_title: {
                        required: true
                    },
                    holiday_date: {
                        required: true
                    },
                    holiday_description: {
                        required: true
                    }                    
                },
                messages: {
                    holiday_title: {
                        required: 'Holiday Title is required'
                    },
                    holiday_date: {
                        required: 'Date of holiday is required'
                    },
                    holiday_description: {
                        required: 'Descritption is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#employee_edit_holiday',function(){
            
            $("#employeeEditHoliday").validate({
                ignore: [],
                rules: {
                    holiday_title: {
                        required: true
                    },
                    holiday_date: {
                        required: true
                    },
                    holiday_description: {
                        required: true
                    }                    
                },
                messages: {
                    holiday_title: {
                        required: 'Holiday Title is required'
                    },
                    holiday_date: {
                        required: 'Date of holiday is required'
                    },
                    holiday_description: {
                        required: 'Descritption is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });



        /* Projects Module  */

        $(document).on('click','#project_search_btn',function(){
            var title = $('#project_title').val();
            var name = $('#client_name').val();
           
            if(title == '' && name == ''){
                $('#project_title').focus();
                $('#client_name').css('border-color','#77A7DB');
                $('#project_title').css('border-color','#77A7DB');
                $('#project_title_error').removeClass('display-none').addClass('display-block');
                $('#client_name_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#client_name').css('border-color','#ccc');
                $('#project_title').css('border-color','#ccc');
                $('#project_title_error').removeClass('display-block').addClass('display-none');
                $('#client_name_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        });

        $(document).on('click','#project_files_add',function(){

            $("#projectFilesAdd").validate({
                ignore: [],
                rules: {
                    title: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    'projectfiles[]': { 
                        required: true, 
                        extension: "png|jpe?g|gif|doc|docx|pdf|txt|xls", 
                        filesize: 3133456
                    }
                },
                messages: {
                    title: {
                        required: "Title must not be empty"
                    },
                    description: {
                        required: "Description must not be empty"
                    },
                    'projectfiles[]': {
                        required: "Please upload a file on empty fields",
                        extension: "Please upload image and text or doc files",
                        filesize: "One of your uploaded filesize must not exceed 3MB"
                    }
                },
                submitHandler: function(form) {
                    //form.submit();
                }
                
               });
            });

            $(document).on('click','#project_files_edit',function(){

                $("#projectFilesEdit").validate({
                    ignore: [],
                    rules: {
                        title: {
                            required: true
                        },
                        description: {
                            required: true
                        }
                    },
                    messages: {
                        title: {
                            required: "Title must not be empty"
                        },
                        description: {
                            required: "Description must not be empty"
                        }
                    },
                    submitHandler: function(form) {
                        //form.submit();
                    }
                    
                   });
                });

                $(document).on('click','#project_task_files',function(){

                    $("#projectTaskFiles").validate({
                        ignore: [],
                        rules: {
                            title: {
                                required: true
                            },
                            description: {
                                required: true
                            },
                            'taskfiles[]': { 
                                required: true, 
                                extension: "png|jpe?g|gif|doc|docx|pdf|txt|xls", 
                                filesize: 3133456
                            }
                        },
                        messages: {
                            title: {
                                required: "Title must not be empty"
                            },
                            description: {
                                required: "Description must not be empty"
                            },
                            'taskfiles[]': {
                                required: "Please upload a file on empty fields",
                                extension: "Please upload image and text or doc files",
                                filesize: "One of your uploaded filesize must not exceed 3MB"
                            }
                        },
                        submitHandler: function(form) {
                            //form.submit();
                        }
                        
                       });
                    });

                    $(document).on('click','#project_task_filesU',function(){

                        $("#projectTaskFilesU").validate({
                            ignore: [],
                            rules: {
                                title: {
                                    required: true
                                },
                                description: {
                                    required: true
                                }
                            },
                            messages: {
                                title: {
                                    required: "Title must not be empty"
                                },
                                description: {
                                    required: "Description must not be empty"
                                }
                            },
                            submitHandler: function(form) {
                                //form.submit();
                            }
                            
                           });
                        });

    // Task Comment

                    $(document).on('click','#task_view_comment',function(){

                        var textareaValue = $('.foeditor-taskview-comment').summernote('code').replace(/&nbsp;/g,'');
                        console.log(textareaValue)
                        if(textareaValue.replace(/ /g,'') == '<p></p>' || textareaValue.replace(/ /g,'') == '<p><br></p>' || textareaValue.replace(/ /g,'') == '')
                        {
                            $('#taskview_comment_error').removeClass('display-none').addClass('display-block');
                            $('.note-editable').trigger('focus');
                            return false;
                        }
                        else
                        {
                            $('#taskview_comment_error').removeClass('display-block').addClass('display-none');
                        }
                    });


        $(document).on('click','#project_add_submit',function(){

            function isRatePresent() {
                console.log($('#fixed_rate').prop('checked'));
                return $('#fixed_rate').prop('checked') == false;
            }

            function isFixedPresent() {
                console.log($('#fixed_rate').prop('checked'));
                return $('#fixed_rate').prop('checked') == true;
            }

            var textareaValue = $('.foeditor-project-add').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
            $('#addproject_description_error').removeClass('display-none').addClass('display-block');
            $('.note-editable').trigger('focus');
            return false;
            }
            else
            {
            $('#addproject_description_error').removeClass('display-block').addClass('display-none');
            console.log('comes');
            $("#projectAddForm").validate({
                ignore: [],
                rules: {
                    project_code: {
                        required: true
                    },
                    project_title: {
                        required: true
                    },
                    client: {
                        required: true
                    },
                    start_date: {
                        required: true
                    },
                    due_date: {
                        required: true
                    },
                    assign_lead: {
                        required: true
                    },
                    'assign_to[]': {
                        required: true
                    },
                    hourly_rate:{
                        required: isRatePresent,
                        number: true
                    },
                    fixed_price:{
                        required: isFixedPresent,
                        number: true
                    },
                    estimate_hours: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    project_code: {
                        required: 'Project is required'
                    },
                    project_title: {
                        required: 'Project is required'
                    },
                    client: {
                        required: 'Please select a client'
                    },
                    start_date: {
                        required: 'Start date is required'
                    },
                    due_date: {
                        required: 'Deadline is required'
                    },
                    assign_lead: {
                        required: 'Please select a lead'
                    },
                    'assign_to[]': {
                        required: 'Please choose a assignee'
                    },
                    hourly_rate:{
                        required: 'Please enter hourly rate'
                        
                    },
                    fixed_price:{
                        required: 'Please enter fixed price'
                    },
                    estimate_hours: {
                        required: 'Estimate hours is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

            }
        });

        $(document).on('click','#project_edit_dashboard',function(){

            function isRatePresent() {
                console.log($('#fixed_rate').prop('checked'));
                return $('#fixed_rate').prop('checked') == false;
            }

            function isFixedPresent() {
                console.log($('#fixed_rate').prop('checked'));
                return $('#fixed_rate').prop('checked') == true;
            }

            var textareaValue = $('.foeditor-project-edit').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
            $('#project_description_error').removeClass('display-none').addClass('display-block');
            $('.note-editable').trigger('focus');
            return false;
            }
            else
            {
            $('#project_description_error').removeClass('display-block').addClass('display-none');
            console.log('comes');
            $("#projectEditForm").validate({
                ignore: [],
                rules: {
                    project_code: {
                        required: true
                    },
                    project_title: {
                        required: true
                    },
                    client: {
                        required: true
                    },
                    start_date: {
                        required: true
                    },
                    due_date: {
                        required: true
                    },
                    assign_lead: {
                        required: true
                    },
                    'assign_to[]': {
                        required: true
                    },
                    hourly_rate:{
                        required: isRatePresent,
                        number: true
                    },
                    fixed_price:{
                        required: isFixedPresent,
                        number: true
                    },
                    estimate_hours: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    project_code: {
                        required: 'Project is required'
                    },
                    project_title: {
                        required: 'Project is required'
                    },
                    client: {
                        required: 'Please select a client'
                    },
                    start_date: {
                        required: 'Start date is required'
                    },
                    due_date: {
                        required: 'Deadline is required'
                    },
                    assign_lead: {
                        required: 'Please select a lead'
                    },
                    'assign_to[]': {
                        required: 'Please choose a assignee'
                    },
                    hourly_rate:{
                        required: 'Please enter hourly rate'
                        
                    },
                    fixed_price:{
                        required: 'Please enter fixed price'
                    },
                    estimate_hours: {
                        required: 'Estimate hours is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

            }
        });

        /* Project checklist */

        $(document).on('click','#project_create_checklist',function(){

            $("#createChecklistForm").validate({
                ignore: [],
                rules: {
                    todo_item: {
                        required: true
                    }
                },
                messages: {
                    todo_item: {
                        required: "Todo Item must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#project_update_checklist',function(){

            $("#editChecklistForm").validate({
                ignore: [],
                rules: {
                    todo_item: {
                        required: true
                    }
                },
                messages: {
                    todo_item: {
                        required: "Todo Item must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        $(document).on('click','#project_add_task',function(){

            $("#projectAddTask").validate({
                ignore: [],
                rules: {
                    task_name_auto: {
                        required: true
                    },
                    description: {
                        required: true
                    }
                    // start_date: {
                    //     required: true
                    // },
                    // due_date: {
                    //     required: true
                    // },
                    // estimate: {
                    //     required: true,
                    //     number: true
                    // },
                    // 'assigned_to[]': {
                    //     required:true
                    // }
                },
                messages: {
                    task_name_auto: {
                        required: "Task Name must not empty"
                    },
                    description: {
                        required: "Description must not empty"
                    }
                    // start_date: {
                    //     required: "Start Date must not empty"
                    // },
                    // due_date: {
                    //     required: "Deadline must not empty"
                    // },
                    // estimate: {
                    //     required: "Estimate must not empty"
                    // },
                    // 'assigned_to[]': {
                    //     required: "Please select a assignee"
                    // }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        $(document).on('click','#project_edit_task',function(){

            $("#projectEditTask").validate({
                ignore: [],
                rules: {
                    task_name: {
                        required: true
                    },
                    description: {
                        required: true
                    }
                    // start_date: {
                    //     required: true
                    // },
                    // due_date: {
                    //     required: true
                    // },
                    // estimate: {
                    //     required: true,
                    //     number: true
                    // },
                    // 'assigned_to[]': {
                    //     required:true
                    // }
                },
                messages: {
                    task_name: {
                        required: "Task Name must not empty"
                    },
                    description: {
                        required: "Description must not empty"
                    }
                    // start_date: {
                    //     required: "Start Date must not empty"
                    // },
                    // due_date: {
                    //     required: "Deadline must not empty"
                    // },
                    // estimate: {
                    //     required: "Estimate must not empty"
                    // },
                    // 'assigned_to[]': {
                    //     required: "Please select a assignee"
                    // }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#project_add_template',function(){

            $("#projectAddTemplateForm").validate({
                ignore: [],
                rules: {
                    template_id: {
                        required: true
                    },
                    start_date: {
                        required: true
                    },
                    due_date: {
                        required: true
                    }
                },
                messages: {
                    template_id: {
                        required: "Please select a template"
                    },
                    start_date: {
                        required: "Start Date must not empty"
                    },
                    due_date: {
                        required: "Deadline must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        
        $(document).on('click','#project_add_milestone',function(){

            $("#projectAddMilestone").validate({
                ignore: [],
                rules: {
                    milestone_name: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    start_date: {
                        required: true
                    },
                    due_date: {
                        required: true
                    }
                },
                messages: {
                    milestone_name: {
                        required: "Milestone Name must not empty"
                    },
                    description: {
                        required: "Description must not empty"
                    },
                    start_date: {
                        required: "Start Date must not empty"
                    },
                    due_date: {
                        required: "Deadline must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#project_edit_milestone',function(){

            $("#projectEditMilestone").validate({
                ignore: [],
                rules: {
                    milestone_name: {
                        required: true
                    },
                    description: {
                        required: true
                    },
                    start_date: {
                        required: true
                    },
                    due_date: {
                        required: true
                    }
                },
                messages: {
                    milestone_name: {
                        required: "Milestone Name must not empty"
                    },
                    description: {
                        required: "Description must not empty"
                    },
                    start_date: {
                        required: "Start Date must not empty"
                    },
                    due_date: {
                        required: "Deadline must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        $(document).on('click','#project_add_mile_task',function(){

            $("#projectAddMileTask").validate({
                ignore: [],
                rules: {
                    task_name: {
                        required: true
                    },
                    description: {
                        required: true
                    }
                    // start_date: {
                    //     required: true
                    // },
                    // due_date: {
                    //     required: true
                    // },
                    // estimate: {
                    //     required: true,
                    //     number: true
                    // },
                    // 'assigned_to[]': {
                    //     required:true
                    // }
                },
                messages: {
                    task_name: {
                        required: "Task Name must not empty"
                    },
                    description: {
                        required: "Description must not empty"
                    }
                    // start_date: {
                    //     required: "Start Date must not empty"
                    // },
                    // due_date: {
                    //     required: "Deadline must not empty"
                    // },
                    // estimate: {
                    //     required: "Estimate must not empty"
                    // },
                    // 'assigned_to[]': {
                    //     required: "Please select a assignee"
                    // }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#project_update_team',function(){

            $("#projectUpdateTeam").validate({
                ignore: [],
                rules: {
                    'assigned_to[]': {
                        required:true
                    }
                },
                messages: {
                    'assigned_to[]': {
                        required: "Please select a assignee"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        /* foeditor-project-discussion */
        $(document).on('click','#project_comment_discussion',function(){

            var textareaValue = $('.foeditor-project-discussion').summernote('code');
            console.log(textareaValue);
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
               $('#project_comment_error').removeClass('display-none').addClass('display-block');
               $('.note-editable').trigger('focus');
               return false;
            }
            else
            {
                $('#project_comment_error').removeClass('display-block').addClass('display-none');
                             
            }
            
        });

        
        $(document).on('click','#project_add_bug',function(){

            $("#projectAddBug").validate({
                ignore: [],
                rules: {
                    issue_ref: {
                        required: true
                    },
                    issue_title: {
                        required: true
                    },
                    bug_description: {
                        required: true
                    },
                    reproducibility: {
                        required: true
                    },
                    reporter: {
                        required: true
                    },
                    priority: {
                        required: true
                    },
                    severity:{
                        required: true
                    },
                    estimate: {
                        required: true,
                        number: true
                    },
                    'assigned_to[]': {
                        required:true
                    }
                },
                messages: {
                    issue_ref: {
                        required: "Id must not empty"
                    },
                    issue_title: {
                        required: "Title must not empty"
                    },
                    bug_description: {
                        required: "Description must not empty"
                    },
                    reproducibility: {
                        required: "Reproducibility must not empty"
                    },
                    reporter: {
                        required: "Please select a reporter"
                    },
                    priority: {
                        required: "Please select a priority"
                    },
                    severity:{
                        required: "Please select a severity"
                    },
                    estimate: {
                        required: "Estimate must not empty"
                    },
                    'assigned_to[]': {
                        required: "Please select a assignee"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#project_edit_bug',function(){

            $("#projectEditBug").validate({
                ignore: [],
                rules: {
                    issue_ref: {
                        required: true
                    },
                    issue_title: {
                        required: true
                    },
                    bug_description: {
                        required: true
                    },
                    reproducibility: {
                        required: true
                    },
                    reporter: {
                        required: true
                    },
                    priority: {
                        required: true
                    },
                    severity:{
                        required: true
                    },
                    estimate: {
                        required: true,
                        number: true
                    },
                    'assigned_to[]': {
                        required: true
                    }
                },
                messages: {
                    issue_ref: {
                        required: "Id must not empty"
                    },
                    issue_title: {
                        required: "Title must not empty"
                    },
                    bug_description: {
                        required: "Description must not empty"
                    },
                    reproducibility: {
                        required: "Reproducibility must not empty"
                    },
                    reporter: {
                        required: "Please select a reporter"
                    },
                    priority: {
                        required: "Please select a priority"
                    },
                    severity:{
                        required: "Please select a severity"
                    },
                    estimate: {
                        required: "Estimate must not empty"
                    },
                    'assigned_to[]': {
                        required: "Please select a assignee"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        
        $(document).on('click','#project_add_mile_task',function(){

            $("#projectAddMileTask").validate({
                ignore: [],
                rules: {
                    task_name: {
                        required: true
                    },
                    description: {
                        required: true
                    }
                    // start_date: {
                    //     required: true
                    // },
                    // due_date: {
                    //     required: true
                    // },
                    // estimate: {
                    //     required: true,
                    //     number: true
                    // },
                    // 'assigned_to[]': {
                    //     required:true
                    // }
                },
                messages: {
                    task_name: {
                        required: "Task Name must not empty"
                    },
                    description: {
                        required: "Description must not empty"
                    }
                    // start_date: {
                    //     required: "Start Date must not empty"
                    // },
                    // due_date: {
                    //     required: "Deadline must not empty"
                    // },
                    // estimate: {
                    //     required: "Estimate must not empty"
                    // },
                    // 'assigned_to[]': {
                    //     required: "Please select a assignee"
                    // }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        /* Calendar Module */

        $(document).on('click','#calendar_add_event',function(){

            $("#calendarAddEvent").validate({
                ignore: [],
                rules: {
                    event_name: {
                        required: true
                    },
                    description: {
                        required: true,
                        maxlength: 120
                    },
                    add_event_date_from: {
                        required: true
                    },
                    add_event_date_to: {
                        required: true
                    },
                    project: {
                        required: true
                    },
                    color: {
                        required:true
                    }
                },
                messages: {
                    event_name: {
                        required: "Event Name must not empty"
                    },
                    description: {
                        required: "Description must not empty",
                        maxlength: "Description shoudn't exceed 120 characters"
                    },
                    add_event_date_from: {
                        required: "From Date must not empty"
                    },
                    add_event_date_to: {
                        required: "To Date must not empty"
                    },
                    project: {
                        required: "Project must not empty"
                    },
                    color: {
                        required: "Color must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#calendar_edit_event',function(){

            $("#calendarEditEvent").validate({
                ignore: [],
                rules: {
                    event_name: {
                        required: true
                    },
                    description: {
                        required: true,
                        maxlength: 120
                    },
                    add_event_date_from: {
                        required: true
                    },
                    add_event_date_to: {
                        required: true
                    },
                    project: {
                        required: true
                    },
                    color: {
                        required:true
                    }
                },
                messages: {
                    event_name: {
                        required: "Event Name must not empty"
                    },
                    description: {
                        required: "Description must not empty",
                        maxlength: "Description shoudn't exceed 120 characters"
                    },
                    add_event_date_from: {
                        required: "From Date must not empty"
                    },
                    add_event_date_to: {
                        required: "To Date must not empty"
                    },
                    project: {
                        required: "Project must not empty"
                    },
                    color: {
                        required: "Color must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


         /* Accounting Module  */

         /* Invoice Submodule  */

         $(document).on('click','#tableinvoices_btn',function(){
            var from = $('#invoice_date_from').val();
            var to = $('#invoice_date_to').val();
            var status =  $('#invoices_status option:selected').val();
           
            if(from == '' && to == '' && status == ''){
                $('#invoice_date_from').focus();
                $('#invoice_date_from').css('border-color','#77A7DB');
                $('#invoice_date_to').css('border-color','#77A7DB');
                $('#invoice_date_from_error').removeClass('display-none').addClass('display-block');
                $('#invoice_date_to_error').removeClass('display-none').addClass('display-block');
                $('#invoices_status_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#invoice_date_from').css('border-color','#ccc');
                $('#invoice_date_to').css('border-color','#ccc');
                $('#invoice_date_from_error').removeClass('display-block').addClass('display-none');
                $('#invoice_date_to_error').removeClass('display-block').addClass('display-none');
                $('#invoices_status_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        });

        $(document).on('click','#invoice_email_template',function(){

            $("#invoiceEmailForm").validate({
                ignore: [],
                rules: {
                    subject: {
                        required: true
                    }
                },
                messages: {
                    subject: {
                        required: 'Subject is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
            });

          
        });

        $(document).on('click','#inview_add_item',function(){

            $("#invoiceAddItem").validate({
                ignore: [],
                rules: {
                    item: {
                        required: true
                    }
                },
                messages: {
                    item: {
                        required: 'Item is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
            });

          
        });

        $(document).on('click','#invoice_reminder_template',function(){

            $("#invoiceReminderForm").validate({
                ignore: [],
                rules: {
                    subject: {
                        required: true
                    }
                },
                messages: {
                    subject: {
                        required: 'Subject is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
            });

          
        });


        $(document).on('click','#invoice_create_submit',function(){

            function isTax1Present() {
                console.log($('#invoice_create_tax1').val());
                var tax1 = $('#invoice_create_tax1').val();
                if(tax1 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax1) && (tax1 === "" || parseInt(tax1) <= 100 || tax1 == 0))
                    {
                        $('#create_invoice_tax1_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#create_invoice_tax1_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_create_tax1').focus();
                    }
                }
                else{
                    $('#create_invoice_tax1_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            function isTax2Present() {
                var tax2 = $('#invoice_create_tax2').val();
                if(tax2 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax2) && (tax2 === "" || parseInt(tax2) <= 100 || tax2 == 0))
                    {
                        $('#create_invoice_tax2_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#create_invoice_tax2_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_create_tax2').focus();
                    }
                }
                else{
                    $('#create_invoice_tax2_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            if(isTax1Present() == true && isTax2Present() == true && isDiscountPresent() == true && isExtrafeePresent() == true){
                
               
               
            }
            else{
                return false;
            }

            function isDiscountPresent() {
                console.log($('#invoice_create_discount').val());
                var discount = $('#invoice_create_discount').val();
                if(discount != '')
                {
                    if(/^(\d*\.)?\d+$/.test(discount) && (discount === "" || parseInt(discount) <= 100 || discount == 0))
                    {
                        $('#create_invoice_discount_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#create_invoice_discount_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_create_discount').focus();
                    }
                }
                else{
                    $('#create_invoice_discount_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            function isExtrafeePresent() {
                console.log($('#invoice_create_extrafee').val());
                var fee = $('#invoice_create_extrafee').val();
                if(fee != '')
                {
                    if(/^(\d*\.)?\d+$/.test(fee))
                    {
                        $('#create_invoice_fee_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#create_invoice_fee_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_create_extrafee').focus();
                    }
                }
                else{
                    $('#create_invoice_fee_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }
            
            var textareaValue = $('.foeditor-invoice-create').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
            $('#create_invoice_error').removeClass('display-none').addClass('display-block');
            $('.note-editable').trigger('focus');
            return false;
            }
            else
            {
            $('#create_invoice_error').removeClass('display-block').addClass('display-none');
            console.log('comes');
            $("#createInvoiceForm").validate({
                ignore: [],
                rules: {
                    client: {
                        required: true
                    },
                    due_date: {
                        required: true
                    }
                },
                messages: {
                    client: {
                        required: 'Please select a client'
                    },
                    due_date: {
                        required: 'Deadline must not empty'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

            }
        });

        $(document).on('click','#invoice_edit_submit',function(){

            function isTax1Present() {
                console.log($('#invoice_edit_tax1').val());
                var tax1 = $('#invoice_edit_tax1').val();
                if(tax1 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax1) && (tax1 === "" || parseInt(tax1) <= 100 || tax1 == 0))
                    {
                        $('#edit_invoice_tax1_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#edit_invoice_tax1_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_create_tax1').focus();
                    }
                }
                else{
                    $('#edit_invoice_tax1_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            function isTax2Present() {
                var tax2 = $('#invoice_edit_tax2').val();
                if(tax2 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax2) && (tax2 === "" || parseInt(tax2) <= 100 || tax2 == 0))
                    {
                        $('#edit_invoice_tax2_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#edit_invoice_tax2_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_create_tax2').focus();
                    }
                }
                else{
                    $('#edit_invoice_tax2_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            if(isTax1Present() == true && isTax2Present() == true && isDiscountPresent() == true && isExtrafeePresent() == true){
                
               
               
            }
            else{
                return false;
            }

            function isDiscountPresent() {
                console.log($('#invoice_edit_discount').val());
                var discount = $('#invoice_edit_discount').val();
                if(discount != '')
                {
                    if(/^(\d*\.)?\d+$/.test(discount) && (discount === "" || parseInt(discount) <= 100 || discount == 0))
                    {
                        $('#edit_invoice_discount_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#edit_invoice_discount_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_edit_discount').focus();
                    }
                }
                else{
                    $('#edit_invoice_discount_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            function isExtrafeePresent() {
                console.log($('#invoice_edit_extrafee').val());
                var fee = $('#invoice_edit_extrafee').val();
                if(fee != '')
                {
                    if(/^(\d*\.)?\d+$/.test(fee))
                    {
                        $('#edit_invoice_fee_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#edit_invoice_fee_error').removeClass('display-none').addClass('display-block');
                        $('#invoice_create_extrafee').focus();
                    }
                }
                else{
                    $('#edit_invoice_fee_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }
            
            var textareaValue = $('.foeditor-invoice-edit').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
            $('#edit_invoice_error').removeClass('display-none').addClass('display-block');
            $('.note-editable').trigger('focus');
            return false;
            }
            else
            {
            $('#edit_invoice_error').removeClass('display-block').addClass('display-none');
            console.log('comes');
            $("#editInvoiceForm").validate({
                ignore: [],
                rules: {
                    client: {
                        required: true
                    },
                    due_date: {
                        required: true
                    },
                    due_date: {
                        required: true
                    }
                },
                messages: {
                    client: {
                        required: 'Please select a client'
                    },
                    due_date: {
                        required: 'Deadline must not empty'
                    },
                    due_date: {
                        required: 'Deadline must not empty'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

            }
        });


        /* Estimates Submodule  */

        $(document).on('click','#esview_add_item',function(){

            $("#estimateAddItem").validate({
                ignore: [],
                rules: {
                    item: {
                        required: true
                    },
                    quantity: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    item: {
                        required: 'Item is required'
                    },
                    quantity: {
                        required: 'Quantity is required'
                       
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
            });

          
        });


        $(document).on('click','#search_estimates_btn',function(){

            var status = $('#estimates_status').val();
            var from = $('#estimates_from').val();
            var to = $('#estimates_to').val();

            if(from == '' && to == '' && status == ''){
                $('#estimates_from').focus();
                $('#estimates_from').css('border-color','#77A7DB');
                $('#estimates_to').css('border-color','#77A7DB');
                $('#estimates_from_error').removeClass('display-none').addClass('display-block');
                $('#estimates_to_error').removeClass('display-none').addClass('display-block');
                $('#estimates_status_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#estimates_from').css('border-color','#ccc');
                $('#estimates_to').css('border-color','#ccc');
                $('#estimates_from_error').removeClass('display-block').addClass('display-none');
                $('#estimates_to_error').removeClass('display-block').addClass('display-none');
                $('#estimates_status_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        
        });


        $(document).on('click','#estimate_email_template',function(){

            $("#estimateEmailForm").validate({
                ignore: [],
                rules: {
                    subject: {
                        required: true
                    }
                },
                messages: {
                    subject: {
                        required: 'Subject is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
            });

          
        });

        

        
        $(document).on('click','#createEstimate',function(){
            var notes = $('.foeditor-estimate-cnote').summernote('code');
            var client = $('#create_estimate_client').val();
            console.log(notes)
            console.log(client)
            //alert(notes);
            
            if(notes == '<p><br></p>' || notes == '')
            {
                $('#estimates_notes_error').removeClass('display-none').addClass('display-block');
                $('.note-editable').trigger('focus');
                
            }
            else
            {
                $('#estimates_notes_error').removeClass('display-block').addClass('display-none');
                                
            }

            if(client == '' || client == null)
            {
                $('#estimates_client_error').removeClass('display-none').addClass('display-block');
                $('#create_estimate_client').select2('open');
            }
            else
            {
                $('#estimates_client_error').removeClass('display-block').addClass('display-none');
                                
            }

            if((client == '' || client == null)  || (notes == '<p><br></p>' || notes == ''))
            {
                console.log('false');
                return false;
            }
            else
            {
                console.log('true');
            }

            function isTax1Present() {
                console.log($('#estimate_create_tax1').val());
                var tax1 = $('#estimate_create_tax1').val();
                if(tax1 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax1) && (tax1 === "" || parseInt(tax1) <= 100 || tax1 == 0))
                    {
                        $('#create_estimate_tax1_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#create_estimate_tax1_error').removeClass('display-none').addClass('display-block');
                        $('#estimate_create_tax1').focus();
                    }
                }
                else{
                    $('#create_estimate_tax1_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            function isTax2Present() {
                var tax2 = $('#estimate_create_tax2').val();
                if(tax2 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax2) && (tax2 === "" || parseInt(tax2) <= 100 || tax2 == 0))
                    {
                        $('#create_estimate_tax2_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#create_estimate_tax2_error').removeClass('display-none').addClass('display-block');
                        $('#estimate_create_tax2').focus();
                    }
                }
                else{
                    $('#create_estimate_tax2_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            if(isTax1Present() == true && isTax2Present() == true && isDiscountPresent() == true && isExtrafeePresent() == true){
                
               
               
            }
            else{
                return false;
            }

            function isDiscountPresent() {
                console.log($('#estimate_create_discount').val());
                var discount = $('#estimate_create_discount').val();
                if(discount != '')
                {
                    if(/^(\d*\.)?\d+$/.test(discount) && (discount === "" || parseInt(discount) <= 100 || discount == 0))
                    {
                        $('#create_estimate_discount_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#create_estimate_discount_error').removeClass('display-none').addClass('display-block');
                        $('#estimate_create_discount').focus();
                    }
                }
                else{
                    $('#create_estimate_discount_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }
            
            
        });

        $(document).on('click','#editEstimate',function(){
            var notes = $('.foeditor-estimate-cnote').summernote('code');
            var client = $('#edit_estimate_client').val();
            console.log(notes)
            console.log(client)
            
            if(notes == '<p><br></p>' || notes == '')
            {
                $('#estimates_notes_error').removeClass('display-none').addClass('display-block');
                $('.note-editable').trigger('focus');
                
            }
            else
            {
                $('#estimates_notes_error').removeClass('display-block').addClass('display-none');
                                
            }

            if(client == '' || client == null)
            {
                $('#estimates_client_error').removeClass('display-none').addClass('display-block');
                $('#create_estimate_client').select2('open');
            }
            else
            {
                $('#estimates_client_error').removeClass('display-block').addClass('display-none');
                                
            }

            if((client == '' || client == null)  || (notes == '<p><br></p>' || notes == ''))
            {
                console.log('false');
                return false;
            }
            else
            {
                console.log('true');
            }

            function isTax1Present() {
                console.log($('#estimate_edit_tax1').val());
                var tax1 = $('#estimate_edit_tax1').val();
                if(tax1 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax1) && (tax1 === "" || parseInt(tax1) <= 100 || tax1 == 0))
                    {
                        $('#edit_estimate_tax1_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#edit_estimate_tax1_error').removeClass('display-none').addClass('display-block');
                        $('#estimate_edit_tax1').focus();
                    }
                }
                else{
                    $('#edit_estimate_tax1_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            function isTax2Present() {
                var tax2 = $('#estimate_edit_tax2').val();
                if(tax2 != '')
                {
                    if(/^(\d*\.)?\d+$/.test(tax2) && (tax2 === "" || parseInt(tax2) <= 100 || tax2 == 0))
                    {
                        $('#edit_estimate_tax2_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#edit_estimate_tax2_error').removeClass('display-none').addClass('display-block');
                        $('#estimate_edit_tax2').focus();
                    }
                }
                else{
                    $('#edit_estimate_tax2_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }

            if(isTax1Present() == true && isTax2Present() == true && isDiscountPresent() == true && isExtrafeePresent() == true){
                
               
               
            }
            else{
                return false;
            }

            function isDiscountPresent() {
                console.log($('#estimate_edit_discount').val());
                var discount = $('#estimate_edit_discount').val();
                if(discount != '')
                {
                    if(/^(\d*\.)?\d+$/.test(discount) && (discount === "" || parseInt(discount) <= 100 || discount == 0))
                    {
                        $('#edit_estimate_discount_error').removeClass('display-block').addClass('display-none');
                        return true;
                    }
                    else
                    {
                        $('#edit_estimate_discount_error').removeClass('display-none').addClass('display-block');
                        $('#estimate_edit_discount').focus();
                    }
                }
                else{
                    $('#edit_estimate_discount_error').removeClass('display-block').addClass('display-none');
                    return true;
                }
            }
            
            
        });



        /* Expense Submodule  */

        $(document).on('click','#search_expenses_btn',function(){

            var category = $('#expenses_category option:selected').val();
            var from = $('#expenses_date_from').val();
            var to = $('#expenses_date_to').val();
            var project = $('#expenes_project').val();
            var client = $('#expenes_client').val();

            if(from == '' && to == '' && category == '' && project == '' && client == ''){
                $('#expenes_project').focus();
                $('#expenes_project').css('border-color','#77A7DB');
                $('#expenes_client').css('border-color','#77A7DB');
                $('#expenses_date_from').css('border-color','#77A7DB');
                $('#expenses_date_to').css('border-color','#77A7DB');
                $('#expenes_client_error').removeClass('display-none').addClass('display-block');
                $('#expenes_project_error').removeClass('display-none').addClass('display-block');
                $('#expenses_date_from_error').removeClass('display-none').addClass('display-block');
                $('#expenses_category_error').removeClass('display-none').addClass('display-block');
                $('#expenses_date_to_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#expenes_project').css('border-color','#ccc');
                $('#expenes_client').css('border-color','#ccc');
                $('#expenses_date_from').css('border-color','#ccc');
                $('#expenses_date_to').css('border-color','#ccc');
                $('#expenes_client_error').removeClass('display-block').addClass('display-none');
                $('#expenes_project_error').removeClass('display-block').addClass('display-none');
                $('#expenses_date_from_error').removeClass('display-block').addClass('display-none');
                $('#expenses_category_error').removeClass('display-block').addClass('display-none');
                $('#expenses_date_to_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        
        });


        $(document).on('click','#accountsCreateExpense',function(){

            $("#createExpenseForm").validate({
                ignore: [],
                rules: {
                    amount: {
                        required: true
                    },
                    project: {
                        required: true
                    },
                    category: {
                        required: false
                    },
                    notes: {
                        required: true
                    },
                    expense_date: {
                        required: true
                    }
                },
                messages: {
                    amount: {
                        required: "Amount must not empty"
                    },
                    project: {
                        required: "Project must not empty"
                    },
                    category: {
                        required: "Category must not empty"
                    },
                    notes: {
                        required: "Notes must not empty"
                    },
                    expense_date: {
                        required: "Expense Date must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#accountsEditExpense',function(){

            $("#editExpenseForm").validate({
                ignore: [],
                rules: {
                    amount: {
                        required: true
                    },
                    project: {
                        required: true
                    },
                    category: {
                        required: true
                    },
                    notes: {
                        required: true
                    },
                    expense_date: {
                        required: true
                    }
                },
                messages: {
                    amount: {
                        required: "Amount must not empty"
                    },
                    project: {
                        required: "Project must not empty"
                    },
                    category: {
                        required: "Category must not empty"
                    },
                    notes: {
                        required: "Notes must not empty"
                    },
                    expense_date: {
                        required: "Expense Date must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        /* Payments  Submodule  */

        $(document).on('click','#payment_edit_submit',function(){

            $("#paymentEditForm").validate({
                ignore: [],
                rules: {
                    amount: {
                        required: true,
                        number:true
                    },
                    notes: {
                        required: true,
                        maxlength: 120
                    },
                    payment_method: {
                        required: true
                    },
                    currency: {
                        required: true
                    },
                    payment_date: {
                        required: true
                    }
                },
                messages: {
                    amount: {
                        required: 'Amount is required'
                    },
                    notes: {
                        required: 'Notes is required',
                        maxlength: "Notes shouldn't exceed 120 characters"
                    },
                    payment_method: {
                        required: 'Please select a payment method'
                    },
                    currency: {
                        required: 'Please select a currency'
                    },
                    payment_date: {
                        required: 'Payment Date is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

         /* TaxRate  Submodule  */

         function isTaxPercentage() {
            var tax = $('#create_taxrate_percent').val();
            console.log(tax);
            if(tax != '')
            {
                $('#create_taxrate_required').removeClass('display-block').addClass('display-none');
                if(/^(\d*\.)?\d+$/.test(tax) && ( tax <= 100 || tax == 0))
                {
                    console.log(true);
                    $('#create_taxrate_error').removeClass('display-block').addClass('display-none');
                    $('#create_taxrate_required').removeClass('display-block').addClass('display-none');
                    return true;
                }
                else
                {
                    $('#create_taxrate_error').removeClass('display-none').addClass('display-block');
                    $('#create_taxrate_percent').focus();
                }
            }
            else{
                $('#create_taxrate_required').removeClass('display-none').addClass('display-block');
                $('#create_taxrate_error').removeClass('display-block').addClass('display-none');
                return false;
            }
        }

        $(document).on('keyup','#create_taxrate_percent',function(){
            isTaxPercentage();
        });

         $(document).on('click','#taxrate_add_submit',function(){

            if(isTaxPercentage() == true){
               
            }
            else{
                return false;
            }

            $("#taxrateAddForm").validate({
                ignore: [],
                rules: {
                    tax_rate_name: {
                        required: true
                    }
                },
                messages: {
                    tax_rate_name: {
                        required: 'Tax Name is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });

           
        });

        function isTaxPercentageEdit() {
            var tax = $('#edit_taxrate_percent').val();
            console.log(tax);
            if(tax != '')
            {
                $('#edit_taxrate_required').removeClass('display-block').addClass('display-none');
                if(/^(\d*\.)?\d+$/.test(tax) && (parseInt(tax) <= 100 || tax == 0))
                {
                    $('#edit_taxrate_error').removeClass('display-block').addClass('display-none');
                    $('#edit_taxrate_required').removeClass('display-block').addClass('display-none');
                    return true;
                }
                else
                {
                    $('#edit_taxrate_error').removeClass('display-none').addClass('display-block');
                    $('#edit_taxrate_percent').focus();
                }
            }
            else{
                $('#edit_taxrate_required').removeClass('display-none').addClass('display-block');
                $('#edit_taxrate_error').removeClass('display-block').addClass('display-none');
                return false;
            }
        }

        $(document).on('keyup','#edit_taxrate_percent',function(){
            isTaxPercentageEdit();
        });

        $(document).on('click','#taxrate_edit_submit',function(){

            if(isTaxPercentageEdit() == true){
                
                $("#taxrateEditForm").validate({
                    ignore: [],
                    rules: {
                        tax_rate_name: {
                            required: true
                        }
                    },
                    messages: {
                        tax_rate_name: {
                            required: 'Tax Name is required'
                        }
                    },
                    submitHandler: function(form) {
                        form.submit();
                    }
                    
                   });
               
            }
            else{
                return false;
            }

           
        });

        /* Items Submodule  */

        $(document).on('click','#items_add_task',function(){

            $("#itemsAddTask").validate({
                ignore: [],
                rules: {
                    task_name: {
                        required: true
                    },
                    description: {
                        required: true,
                        maxlength: 120
                    },
                    estimate: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    task_name_auto: {
                        required: "Task Name must not empty"
                    },
                    description: {
                        required: "Description must not empty",
                        maxlength: "Description shoudn't exceed 120 characters"
                    },
                    estimate: {
                        required: "Estimate Hours must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#items_edit_task',function(){

            $("#itemsEditTask").validate({
                ignore: [],
                rules: {
                    task_name: {
                        required: true
                    },
                    description: {
                        required: true,
                        maxlength: 120
                    },
                    estimate: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    task_name_auto: {
                        required: "Task Name must not empty"
                    },
                    description: {
                        required: "Description must not empty",
                        maxlength: "Description shoudn't exceed 120 characters"
                    },
                    estimate: {
                        required: "Estimate Hours must not empty"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#items_add_item',function(){

            $("#itemsAddItem").validate({
                ignore: [],
                rules: {
                    item_name: {
                        required: true
                    },
                    item_desc: {
                        required: true,
                        maxlength: 120
                    },
                    quantity: {
                        required: true,
                        number: true
                    },
                    unit_cost: {
                        required: true,
                        number: true
                    },
                    item_tax_rate:{
                        required: true
                    }
                },
                messages: {
                    task_name: {
                        required: "Task Name must not empty"
                    },
                    item_desc: {
                        required: "Description must not empty",
                        maxlength: "Description shoudn't exceed 120 characters"
                    },
                    quantity: {
                        required: 'Quantity is required'
                    },
                    unit_cost: {
                        required: 'Unit cost is required'
                    },
                    item_tax_rate:{
                        required: 'Please select a tax rate'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#items_edit_item',function(){

            $("#itemsEditItem").validate({
                ignore: [],
                rules: {
                    item_name: {
                        required: true
                    },
                    item_desc: {
                        required: true,
                        maxlength: 120
                    },
                    quantity: {
                        required: true,
                        number: true
                    },
                    unit_cost: {
                        required: true,
                        number: true
                    },
                    item_tax_rate:{
                        required: true
                    }
                },
                messages: {
                    task_name: {
                        required: "Task Name must not empty"
                    },
                    item_desc: {
                        required: "Description must not empty",
                        maxlength: "Description shoudn't exceed 120 characters"
                    },
                    quantity: {
                        required: 'Quantity is required'
                    },
                    unit_cost: {
                        required: 'Unit cost is required'
                    },
                    item_tax_rate:{
                        required: 'Please select a tax rate'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        /* Message Module */

        $(document).on('click','#message_send_email',function(){
            
            var username = $('#username_select').val();
            console.log(username);
            if(username == null || username == '')
            {
               $('#username_message_error').removeClass('display-none').addClass('display-block');
               $('#username_select').focus();
               return false;
            }
            else
            {
                $('#username_message_error').removeClass('display-block').addClass('display-none');
            }



            var textareaValue = $('.foeditor-send-message').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
               $('#send_message_error').removeClass('display-none').addClass('display-block');
               $('.note-editable').trigger('focus');
               return false;
            }
            else
            {
                $('#send_message_error').removeClass('display-block').addClass('display-none');
            }

            
            
        });

        $(document).on('click','#send_message_conversation',function(){
            
            var textareaValue = $('.foeditor-send-conversation').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
               $('#send_conversation_error').removeClass('display-none').addClass('display-block');
               $('.note-editable').trigger('focus');
               return false;
            }
            else
            {
                $('#send_conversation_error').removeClass('display-block').addClass('display-none');
            }

            
            
        });

        /* payroll  */

        $(document).on('click','#payroll_salary_edit',function(){

            $("#payrollSalaryEdit").validate({
                ignore: [],
                rules: {
                    user_salary_amount: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    user_salary_amount: {
                        required: "Salary amount must not empty",
                        number: 'Please enter a valid amount'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#payroll_create_payslip',function(){

            $("#payrollPaySlip").validate({
                ignore: [],
                rules: {
                    payslip_ded_others: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_ded_fund: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_ded_welfare: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_ded_prof: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_ded_leave: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_ded_pf: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_ded_esi: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_ded_tds: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_others: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_medical_allowance: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_allowance: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_conveyance: {
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_hra: {
                        required: true,
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_da: {
                        required: true,
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_basic: {
                        required: true,
                        number: {
                            param:true,
                            depends: $('#payslip_ded_others').val() != ''
                        }
                    },
                    payslip_month: {
                        required: true
                    },
                    payslip_year: {
                        required: true
                    }
                },
                messages: {
                    payslip_ded_others: {
                        number: 'Please enter a valid amount'
                    },
                    payslip_ded_fund: {
                        number: 'Please enter a valid fund'
                    },
                    payslip_ded_welfare: {
                        number: 'Please enter a valid welfare'
                    },
                    payslip_ded_prof: {
                        number: 'Please enter a valid amount'
                    },
                    payslip_ded_leave: {
                        number: 'Please enter a valid amount'
                    },
                    payslip_ded_pf: {
                        number: 'Please enter a valid PF'
                    },
                    payslip_ded_esi: {
                        number: 'Please enter a valid ESI'
                    },
                    payslip_ded_tds: {
                        number: 'Please enter a valid TDS'
                    },
                    payslip_others: {
                        number: 'Please enter a valid amount'
                    },
                    payslip_medical_allowance: {
                        number: 'Please enter a valid allowance'
                    },
                    payslip_allowance: {
                        number: 'Please enter a valid allowance'
                    },
                    payslip_conveyance: {
                        number: 'Please enter a valid conveyance'
                    },
                    payslip_hra: {
                        number: 'Please enter a valid HRA'
                    },
                    payslip_da: {
                        number: 'Please enter a valid DA'
                    },
                    payslip_basic: {
                        number: 'Please enter a valid basic'
                    },
                    payslip_month: {
                        required: 'Please select a month'
                    },
                    payslip_year: {
                        required: 'Please select a year'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        /* Tickets Module  */

        $(document).on('click','#ticket_search_btn',function(){

            var priority = $('#ticked_priority option:selected').val();
            var from = $('#ticket_from').val();
            var to = $('#ticket_to').val();
            var status = $('#ticket_status option:selected').val();
            var name = $('#employee_name').val();

            if(from == '' && to == '' && priority == '' && status == '' && name == ''){
                $('#employee_name').focus();
                $('#employee_name').css('border-color','#77A7DB');
                $('#ticket_to').css('border-color','#77A7DB');
                $('#ticket_from').css('border-color','#77A7DB');
                $('#employee_name_error').removeClass('display-none').addClass('display-block');
                $('#ticked_priority_error').removeClass('display-none').addClass('display-block');
                $('#ticket_from_error').removeClass('display-none').addClass('display-block');
                $('#ticket_to_error').removeClass('display-none').addClass('display-block');
                $('#ticket_status_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#employee_name').css('border-color','#ccc');
                $('#ticket_to').css('border-color','#ccc');
                $('#ticket_from').css('border-color','#ccc');
                $('#employee_name_error').removeClass('display-block').addClass('display-none');
                $('#ticked_priority_error').removeClass('display-block').addClass('display-none');
                $('#ticket_from_error').removeClass('display-block').addClass('display-none');
                $('#ticket_to_error').removeClass('display-block').addClass('display-none');
                $('#ticket_status_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        
        });

        $(document).on('click','#ticket_select_dept',function(){

            $("#ticketSelectDept").validate({
                ignore: [],
                rules: {
                    dept: {
                        required: true
                    }
                },
                messages: {
                    dept: {
                        required: "Please select a department"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#tickets_create_ticket',function(){

            var textareaValue = $('.foeditor-ticket-message').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
                $('#addticket_message_error').removeClass('display-none').addClass('display-block');
                $('.note-editable').trigger('focus');
                return false;
            }
            else
            {
                $('#addticket_message_error').removeClass('display-block').addClass('display-none');
                                
            }
                
            $("#ticketCreateForm").validate({
                ignore: [],
                rules: {
                    dept: {
                        required: true
                    },
                    ticket_code: {
                        required: true
                    },
                    subject: {
                        required: true
                    },
                    reporter: {
                        required: true
                    },
                    priority: {
                        required: true
                    }
                },
                messages: {
                    dept: {
                        required: "Please select a department"
                    },
                    ticket_code: {
                        required: 'Ticket code is required'
                    },
                    subject: {
                        required: 'Subject must not empty'
                    },
                    reporter: {
                        required: 'Please select a reporter'
                    },
                    priority: {
                        required: 'Please select a priority'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#tickets_edit_ticket',function(){

            var textareaValue = $('.foeditor-ticket-messageU').summernote('code');
            console.log(textareaValue)
            if(textareaValue == '<p><br></p>' || textareaValue == '')
            {
                $('#editTicket_message_error').removeClass('display-none').addClass('display-block');
                $('.note-editable').trigger('focus');
                return false;
            }
            else
            {
                $('#editTicket_message_error').removeClass('display-block').addClass('display-none');
                                
            }
                
            $("#ticketEditForm").validate({
                ignore: [],
                rules: {
                    department: {
                        required: true
                    },
                    ticket_code: {
                        required: true
                    },
                    subject: {
                        required: true
                    },
                    reporter: {
                        required: true
                    },
                    priority: {
                        required: true
                    }
                },
                messages: {
                    department: {
                        required: "Please select a department"
                    },
                    ticket_code: {
                        required: 'Ticket code is required'
                    },
                    subject: {
                        required: 'Subject must not empty'
                    },
                    reporter: {
                        required: 'Please select a reporter'
                    },
                    priority: {
                        required: 'Please select a priority'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });



        /* Users Module  */

        $(document).on('click','#users_search_btn',function(){

            var role = $('#user_role option:selected').val();
            var client = $('#company option:selected').val();
            var name = $('#username').val();

            if(role == '' && client == '' && name == ''){
                $('#username').focus();
                $('#username').css('border-color','#77A7DB');
                $('#user_role_error').removeClass('display-none').addClass('display-block');
                $('#company_error').removeClass('display-none').addClass('display-block');
                $('#username_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#username').css('border-color','#ccc');
                $('#user_role_error').removeClass('display-block').addClass('display-none');
                $('#company_error').removeClass('display-block').addClass('display-none');
                $('#username_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        
        });

        $(document).on('click','#user_add_new',function(){
    
            $.validator.addMethod("phonevalidation",
                function(value, element) {
                    return /^[\s\+]?(\([0-9]{2}\s?)?((\([0-9]{3}\))|[0-9]{3})[\s\-]?[\0-9]{3}[\s\-]?[0-9]{4}$/.test(value);
                      
                },
            "Please enter a valid phone number."
            );
    
            $.validator.addMethod("emailvalidation",
                    function(value, element) {
                            return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                    },
            "Please enter a valid email address."
            );
    
            $("#addNewUser").validate({
                onsubmit: true,
                ignore: [] ,
                rules: {
                    fullname: {
                        required: true
                    },
                    username: {
                        required: true
                    },
                    email: {
                        required: true,
                        emailvalidation: 'emailvalidation'
                    },
                    phone: {
                        required: true,
                        phonevalidation: 'phonevalidation'
                    },
                    password: {
                        required: true,
                        minlength : 6
                    },
                    confirm_password: {
                        required: true,
                        minlength : 6,
					    equalTo : "#password"
                    },
                    company:{
                        required: true
                    },
                    role: {
                        required:true
                    }
                },
                messages: {
                    fullname: {
                        required: "Name must not be empty"
                    },
                    username: {
                        required: "Username is required"
                    },
                    email: {
                        required: "Email Id is required",
                        emailvalidation: "Please enter a valid email Id"
                    },
                    phone: {
                        required: 'Phone number is required',
                        minlength: "Minimum Length Should be 7 digit",
                        maxlength: "Maximum Length Should be 15 digit",
                        phonevalidation: "Entered Number is Invalid"
                    },
                    password: {
                        required: "Password is required"
                    },
                    confirm_password: {
                        required: "Password is required",
					    equalTo : "Passwords are mismatched"
                    },
                    company:{
                        required: "Please select a company"
                    },
                    role: {
                        required:"Please select a role"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
            });
    
            $(document).on('click','#update_exist_user',function(){

                console.log('update');
    
                $.validator.addMethod("phonevalidation",
                    function(value, element) {
                        return /^[\s\+]?(\([0-9]{2}\s?)?((\([0-9]{3}\))|[0-9]{3})[\s\-]?[\0-9]{3}[\s\-]?[0-9]{4}$/.test(value);
                          
                    },
                "Please enter a valid phone number."
                );
        
                $.validator.addMethod("mobilevalidation",
                function(value, element) {
                        return /^(\+\d{1,3}[- ]?)?\d{10}$/.test(value);
                },
                "Please enter a valid mobile number."
                );

                $("#editExistUser").validate({
                    onsubmit: true,
                    ignore: [] ,
                    rules: {
                        fullname: {
                            required: true
                        },
                        phone: {
                            required: true,
                            phonevalidation: 'phonevalidation'
                        },
                        company:{
                            required: true
                        },
                        hourly_rate: {
                            required: true,
                            number: true
                        },
                        mobile: {
                            required: true,
                            mobilevalidation: 'mobilevalidation'
                        }
                    },
                    messages: {
                        fullname: {
                            required: "Name must not be empty"
                        },
                        phone: {
                            required: 'Phone number is required',
                            minlength: "Minimum Length Should be 7 digit",
                            maxlength: "Maximum Length Should be 15 digit",
                            phonevalidation: "Entered Number is Invalid"
                        },
                        mobile: {
                            required: 'Mobile number is required',
                            mobilevalidation: "Entered Number is Invalid"
                        },
                        company:{
                            required: "Please select a company"
                        },
                        hourly_rate: {
                            required:"Please enter the rate"
                        }
                    },
                    submitHandler: function(form) {
                        form.submit();
                    }
                    
                   });
        });

        $(document).on('click','#paypal_subscription',function(){

            $("#paypal_form").validate({
                ignore: [],
                rules: {
                    user_count: {
                        required: true,
                        number: true
                    },
                    sub_amount: {
                        required: true,
                        number: true
                    },
                    status: {
                        required:true
                    }
                },
                messages: {
                    user_count: {
                        required: "Please enter the count"
                    },
                    sub_amount: {
                        required: "Please enter the amount"
                    },
                    status: {
                        required:"Please select the payment type"
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        /* settings module */

        $(document).on('click','#general_settings_save',function(){

            $.validator.addMethod("phonevalidation",
                    function(value, element) {
                        return /^[\s\+]?(\([0-9]{2}\s?)?((\([0-9]{3}\))|[0-9]{3})[\s\-]?[\0-9]{3}[\s\-]?[0-9]{4}$/.test(value);
                          
                    },
                "Please enter a valid phone number."
            );

            $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
            "Please enter a valid email address."
            );

            jQuery.validator.addMethod("zipcode", 
            function(value, element) {
                return this.optional(element) || /^\d{6}(?:-\d{4})?$/.test(value);
              }, 
            "Please provide a valid zipcode.");

            console.log($('#general_settings_fax').val())

            $("#settingsGeneralForm").validate({
                ignore: [],
                rules: {
                    company_name: {
                        required: true
                    },
                    company_legal_name: {
                        required : true
                    },
                    contact_person: {
                        required: true
                    },
                    company_address: {
                        required: true
                    },
                    company_zip_code: {
                        required: true,
                        number: true,
                        zipcode: 'zipcode'
                    },
                    company_city : {
                        required: true
                    },
                    company_state: {
                        required: true
                    },
                    company_country: {
                        required: true
                    },
                    company_email: {
                        required: true,
                        emailvalidation:'emailvalidation'
                    },
                    company_phone: {
                        required: true,
                        phonevalidation:'phonevalidation'
                    },
                    company_phone_2 : {
                        number: {
                            param:true,
                            depends: $('#general_settings_phone2').val().length > 0
                        },
                        max: {
                            param:12,
                            depends: $('#general_settings_phone2').val().length > 0
                        },
                        min: {
                            param:10,
                            depends: $('#general_settings_phone2').val().length > 0
                        }
                    },
                    company_mobile: {
                        required: true,
                        phonevalidation:'phonevalidation'
                    },
                    company_fax: {
                        number: {
                            param:true,
                            depends: $('#general_settings_fax').val().length > 0
                        },
                        max: {
                            param:12,
                            depends: $('#general_settings_fax').val().length > 0
                        },
                        min: {
                            param:10,
                            depends: $('#general_settings_fax').val().length > 0
                        }
                    },
                    company_vat: {
                        number: {
                            param:true,
                            depends: $('#general_settings_vat').val() != ''
                        },
                        max:{
                            param:100,
                            depends: $('#general_settings_vat').val() != ''
                        },
                        min:{
                            param:0,
                            depends: $('#general_settings_vat').val() != ''
                        }
                    },
                    company_domain: {
                        url:{
                            param:true,
                            depends: $('#general_settings_domain').val() != ''
                        }
                    }
                },
                messages: {
                    company_name: {
                        required: "Company name is required"
                    },
                    company_legal_name :{
                        required : "Company legal name is required"
                    },
                    contact_person:{
                        required: 'Contact person is required'
                    },
                    company_address: {
                        required: 'Address is required'
                    },
                    company_zip_code: {
                        required: 'Zipcode is required'
                    },
                    company_city : {
                        required: 'City is required'
                    },
                    company_state: {
                        required: 'State is required'
                    },
                    company_country: {
                        required: 'Please select a country'
                    },
                    company_email: {
                        required: 'Email is required'
                    },
                    company_phone: {
                        required: 'Phone is required'
                    },
                    company_phone_2 : {
                        //required: true
                    },
                    company_mobile: {
                        required: 'Mobile is required'
                    },
                    company_fax: {
                        phonevalidation: 'Please enter a valid fax number'
                    },
                    company_domain: {
                        
                    },
                    company_vat: {
                        max:'Vat should less than 100',
                        min:'Vat should greater than 0'
                    }

                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#system_settings_save',function(){

            $("#settingsSystemForm").validate({
                ignore: [],
                rules: {
                    timezone: {
                        required: true
                    },
                    default_currency: {
                        required : true
                    },
                    default_currency_symbol: {
                        required: true
                    },
                    currency_position: {
                        required: true
                    },
                    currency_decimals: {
                        required: true
                    },
                    decimal_separator : {
                        required: true
                    },
                    thousand_separator: {
                        required: true
                    },
                    default_tax: {
                        number: {
                            param:true,
                            depends: $('#system_settings_tax1').val().length > 0
                        },
                        max: {
                            param:12,
                            depends: $('#system_settings_tax1').val().length > 0
                        },
                        min: {
                            param:10,
                            depends: $('#system_settings_tax1').val().length > 0
                        }
                    },
                    default_tax2: {
                        number: {
                            param:true,
                            depends: $('#system_settings_tax2').val().length > 0
                        },
                        max: {
                            param:12,
                            depends: $('#system_settings_tax2').val().length > 0
                        },
                        min: {
                            param:10,
                            depends: $('#system_settings_tax2').val().length > 0
                        }
                    },
                    tax_decimals: {
                        required: true
                    },
                    quantity_decimals : {
                        required: true
                    },
                    date_format: {
                        required: true
                    },
                    enable_languages: {
                        required: true
                    },
                    use_gravatar: {
                        required: true
                    },
                    allow_client_registration: {
                        required: true
                    },
                    file_max_size: {
                        required: true,
                        number:true
                    },
                    allowed_files: {
                        required: true
                    },
                    client_create_project: {
                        required: true
                    },
                    auto_close_ticket: {
                        required: true,
                        number:true
                    },
                    ticket_start_no: {
                        required: true,
                        number:true
                    },
                    ticket_default_department: {
                        required: true
                    }
                },
                messages: {
                    timezone: {
                        required: 'Timezone is required'
                    },
                    default_currency: {
                        required : 'Currency is required'
                    },
                    default_currency_symbol: {
                        required: 'Currency symbol is required'
                    },
                    currency_position: {
                        required: 'Currency position is required'
                    },
                    currency_decimals: {
                        required: 'Please select a decimal'
                    },
                    decimal_separator : {
                        required: 'Decimal seperator is required'
                    },
                    thousand_separator: {
                        required: 'Thousand seperator is required'
                    },
                    default_tax: {
                        //required: true
                    },
                    default_tax2: {
                        //required: true
                    },
                    tax_decimals: {
                        required: 'Please select a decimal'
                    },
                    quantity_decimals : {
                        required: 'Please select a decimal'
                    },
                    date_format: {
                        required: 'Please select a date format'
                    },
                    file_max_size: {
                        required: 'Please enter file size'
                    },
                    allowed_files: {
                        required: 'Allowed files is required'
                    },
                    auto_close_ticket: {
                        required: 'Close ticket is required'
                    },
                    ticket_start_no: {
                        required: 'Start number is required'
                    },
                    ticket_default_department: {
                        required: 'Please select a department'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        $(document).on('click','#settings_add_category',function(){

            $("#settingsAddCategory").validate({
                ignore: [],
                rules: {
                    cat_name: {
                        required: true
                    },
                    module: {
                        required : true
                    }
                },
                messages: {
                    cat_name: {
                        required: 'Category name is required'
                    },
                    module: {
                        required : 'Module is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_edit_category',function(){

            $("#settingsEditCategory").validate({
                ignore: [],
                rules: {
                    cat_name: {
                        required: true
                    },
                    module: {
                        required : true
                    }
                },
                messages: {
                    cat_name: {
                        required: 'Category name is required'
                    },
                    module: {
                        required : 'Module is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        $(document).on('click','#settings_system_slack',function(){

            $("#settingsSystemSlack").validate({
                ignore: [],
                rules: {
                    slack_username: {
                        required: true
                    },
                    slack_channel: {
                        required : true
                    },
                    slack_webhook: {
                        required: true,
                        url:true
                    }
                },
                messages: {
                    slack_username: {
                        required: 'Username is required'
                    },
                    slack_channel: {
                        required : 'Channel is required'
                    },
                    slack_webhook: {
                        required: 'Webhook is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_email_submit',function(){

            $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
            "Please enter a valid email address."
            );

            function alternates() {
                return $('#use_alternate_emails').prop("checked") == true; // Has at least one letter
            }
            

            console.log($('#use_alternate_emails').prop("checked"))
            console.log($('#use_alternate_emails').val());

            $("#settingsEmailForm").validate({
                ignore: [],
                rules: {
                    company_email: {
                        required: true,
                        emailvalidation:'emailvalidation'
                    },
                    billing_email: {
                        required: {
                            param:true,
                            depends: alternates
                        },
                        emailvalidation: {
                            param:'emailvalidation',
                            depends: alternates
                        }
                    },
                    billing_email_name: {
                        required: {
                            param:true,
                            depends: alternates
                        }
                    },
                    support_email: {
                        required: {
                            param:true,
                            depends: alternates
                        },
                        emailvalidation: {
                            param:'emailvalidation',
                            depends: alternates
                        }
                    },
                    support_email_name: {
                        required: {
                            param:true,
                            depends: alternates
                        }
                    },
                    protocol : {
                        required: true
                    },
                    smtp_host: {
                        required: true
                    },
                    smtp_user: {
                        required: true
                    },
                    smtp_pass: {
                        required: true
                    },
                    smtp_port: {
                        required: true
                    }
                },
                messages: {
                    company_email: {
                        required: 'Email is required'
                    },
                    billing_email: {
                        required : 'Billing email is required'
                    },
                    billing_email_name: {
                        required: 'Billing name is required'
                    },
                    support_email: {
                        required: 'Support email is required'
                    },
                    support_email_name: {
                        required: 'Support name is required'
                    },
                    protocol : {
                        required: 'Email protocol is required'
                    },
                    smtp_host: {
                        required: 'SMTP HOST is required'
                    },
                    smtp_user: {
                        required: 'SMTP USER is required'
                    },
                    smtp_pass: {
                        required: 'SMTP PASSWORD is required'
                    },
                    smtp_port: {
                        required: 'SMTP PORT is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_email_piping',function(){

            console.log($('#check_mail_imap').val());

            function alternates() {
                return $('#check_mail_imap').prop("checked") == true; // Has at least one letter
            }

            $("#settingsEmailPipes").validate({
                ignore: [],
                rules: {
                    mail_imap_host: {
                        required: {
                            param:true,
                            depends: alternates
                        }
                    },
                    mail_username: {
                        required: {
                            param:true,
                            depends: alternates
                        }
                    },
                    mail_password: {
                        required: {
                            param:true,
                            depends: alternates
                        }
                    },
                    mail_port: {
                        required: {
                            param:true,
                            depends: alternates
                        }
                    },
                    mail_flags: {
                        required: {
                            param:true,
                            depends: alternates
                        }
                    }
                },
                messages: {
                    mail_imap_host: {
                        required: 'IMAP HOST is required'
                    },
                    mail_username: {
                        required : 'IMAP username is required'
                    },
                    mail_password: {
                       required: 'IMAP password is required'
                    },
                    mail_port: {
                        required: 'Mail port is required'
                    },
                    mail_flags: {
                        required: 'Mail flag is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#lead_reporter_add',function(){

            // var user_email = $('#reporter_email').val();
                    // $.post(base_url+'settings/check_reporter_mail/',{user_email:user_email},function(res){
                    //     // alert(res); return false;
                    //     if(res == 'exists'){
                    //         $('#reporter_email_error_exist').css('display','');
                    //         return false;
                    //     }
                    // });
                    // return false;

            $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
            "Please enter a valid email address."
            );

            $("#leadReporterAdd").validate({
                ignore: [],
                rules: {
                    reporter_name: {
                        required:true
                    },
                    reporter_email: {
                        required:true,
                        emailvalidation:'emailvalidation',

                    }
                },
                messages: {
                    reporter_name: {
                        required: 'Reporter name is required'
                    },
                    reporter_email: {
                        required : 'Reporter email is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#lead_reporter_edit',function(){

            $.validator.addMethod("emailvalidation",
                function(value, element) {
                        return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
                },
            "Please enter a valid email address."
            );

            $("#leadReporterEdit").validate({
                ignore: [],
                rules: {
                    reporter_name: {
                        required:true
                    },
                    reporter_email: {
                        required:true,
                        emailvalidation:'emailvalidation'
                    }
                },
                messages: {
                    reporter_name: {
                        required: 'Reporter name is required'
                    },
                    reporter_email: {
                        required : 'Reporter email is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_payment_submit',function(){

            $.validator.addMethod("emailvalidation",
            function(value, element) {
                    return /^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/.test(value);
            },
        "Please enter a valid email address."
        );

            $("#settingsPaymentForm").validate({
                ignore: [],
                rules: {
                    paypal_email: {
                        required: true,
                        emailvalidation:'emailvalidation'
                    },
                    stripe_private_key: {
                        required : true
                    },
                    stripe_public_key: {
                       required: true
                    }
                },
                messages: {
                    paypal_email: {
                        required: 'Paypal email is required'
                    },
                    stripe_private_key: {
                        required : 'Stripe private key is required'
                    },
                    stripe_public_key: {
                       required: 'Stripe public key is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_invoice_submit',function(){

            $("#settingsInvoiceForm").validate({
                ignore: [],
                rules: {
                    invoice_color: {
                        required: true
                    },
                    invoice_prefix: {
                        required : true
                    },
                    invoices_due_after: {
                       required: true,
                       number:true,
                       min:0
                    },
                    invoice_start_no: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    invoice_color: {
                        required: 'Invoice color is required'
                    },
                    invoice_prefix: {
                        required : 'Invoice prefix is required'
                    },
                    invoices_due_after: {
                       required: 'Invoice due is required'
                    },
                    invoice_start_no: {
                        required: 'Invoice start number is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });


        $(document).on('click','#settings_estimates_submit',function(){

            $("#settingsEstimatesForm").validate({
                ignore: [],
                rules: {
                    estimate_color: {
                        required: true
                    },
                    estimate_prefix: {
                        required : true
                    },
                    estimate_start_no: {
                        required: true,
                        number:0
                    }
                },
                messages: {
                    estimate_color: {
                        required: 'Estimate color is required'
                    },
                    estimate_prefix: {
                        required : 'Estimate prefix is required'
                    },
                    estimate_start_no: {
                        required: 'Estimate start number is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_theme_submit',function(){

            $("#settingsThemeForm").validate({
                ignore: [],
                rules: {
                    website_name: {
                        required: true
                    },
                    login_title: {
                        required : true
                    }
                },
                messages: {
                    website_name: {
                        required: 'Sitename is required'
                    },
                    login_title: {
                        required : 'Login title is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_cron_submit',function(){

            $("#settingsCronForm").validate({
                ignore: [],
                rules: {
                    cron_key: {
                        required: true
                    }
                },
                messages: {
                    cron_key: {
                        required: 'Key is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_department_submit',function(){

            $("#settingsDepartmentForm").validate({
                ignore: [],
                rules: {
                    deptname: {
                        required: true
                    }
                },
                messages: {
                    deptname: {
                        required: 'Department is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#settings_designation_submit',function(){

            $("#settingsDesignationForm").validate({
                ignore: [],
                rules: {
                    department_id: {
                        required: true
                    },
                    designation: {
                        required : true
                    }
                },
                messages: {
                    department_id: {
                        required: 'Department is required'
                    },
                    designation: {
                        required : 'Designation is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#add_leave_type',function(){

            $("#addLeaveType").validate({
                ignore: [],
                rules: {
                    leave_type: {
                        required: true
                    },
                    leave_days: {
                        required : true
                    }
                },
                messages: {
                    leave_type: {
                        required: 'Leave type is required'
                    },
                    leave_days: {
                        required : 'Leave days is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });

        $(document).on('click','#ha_dra_submit',function(){

            $("#HaDraForm").validate({
                ignore: [],
                rules: {
                    salary_da: {
                        required: true,
                        max:55,
                        min:0
                    },
                    salary_hra: {
                        required : true,
                        max:25,
                        min:0
                    }
                },
                messages: {
                    salary_da: {
                        required: 'DA is required'
                    },
                    salary_hra: {
                        required : 'HRA is required'
                    }
                },
                submitHandler: function(form) {
                    form.submit();
                }
                
               });
        });



        /* Activity Module  */

        $(document).on('click','#activity_search',function(){

            var from = $('#ser_activity_date_from').val();
            var to = $('#ser_activity_date_to').val();
            
            if(from == '' && to == ''){
                $('#ser_activity_date_from').focus();
                $('#ser_activity_date_from').css('border-color','#77A7DB');
                $('#ser_activity_date_to').css('border-color','#77A7DB');
                $('#ser_activity_date_from_error').removeClass('display-none').addClass('display-block');
                $('#ser_activity_date_to_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#ser_activity_date_from').css('border-color','#ccc');
                $('#ser_activity_date_to').css('border-color','#ccc');
                $('#ser_activity_date_from_error').removeClass('display-block').addClass('display-none');
                $('#ser_activity_date_to_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        
        });


        /* Timesheets Module  */

        $(document).on('click','#timesheet_search_btn',function(){

            console.log('comes');

            var from = $('#timesheet_date_from').val();
            var to = $('#timesheet_date_to').val();
            var id = $('#employee_id option:selected').val();
            
            if(from == '' && to == '' && id == ''){
                $('#timesheet_date_from').focus();
                $('#timesheet_date_from').css('border-color','#77A7DB');
                $('#timesheet_date_to').css('border-color','#77A7DB');
                $('#timesheet_date_from_error').removeClass('display-none').addClass('display-block');
                $('#employee_id_error').removeClass('display-none').addClass('display-block');
                $('#timesheet_date_to_error').removeClass('display-none').addClass('display-block');
                console.log("invalid")
                return false;
            }
            else{
                $('#timesheet_date_to').css('border-color','#ccc');
                $('#timesheet_date_from').css('border-color','#ccc');
                $('#timesheet_date_from_error').removeClass('display-block').addClass('display-none');
                $('#timesheet_date_to_error').removeClass('display-block').addClass('display-none');
                $('#employee_id_error').removeClass('display-block').addClass('display-none');
                console.log("valid")
            }
        
        });

        $(document).on('click','#new_timesheet_btn',function(){

            // $("#add_timeline").validate({
            //     ignore: [],
            //     rules: {
            //         project_name: {
            //             required: true
            //         },
            //         timeline_date: {
            //             required: true
            //         },
            //         timeline_desc: {
            //             required: true,
            //             maxlength:120
            //         },
            //         timeline_hours: {
            //             required: true,
            //             number: true
            //         }
            //     },
            //     messages: {
            //         project_name: {
            //             required: "Project name must not empty"
            //         },
            //         timeline_date: {
            //             required: "Date is required"
            //         },
            //         timeline_hours: {
            //             required: "Hours field is required"
            //         },
            //         timeline_desc: {
            //             required: "Description must not empty"
            //         }
            //     },
            //     submitHandler: function(form) {
            //         form.submit();
            //     }
                
            //    });
        });

        $(document).on('click','#timesheet_edit_submit',function(){
            console.log('hrms')
            $("#edit_timesheet").validate({
               rules: {
                    project_name: {
                        required: true
                    },
                    timeline_date: {
                        required: true
                    },
                    timeline_desc: {
                        required: true,
                        maxlength:120
                    },
                    timeline_hours: {
                        required: true,
                        number: true
                    }
                },
                messages: {
                    project_name: {
                        required: "Project name must not empty"
                    },
                    timeline_date: {
                        required: "Date is required"
                    },
                    timeline_hours: {
                        required: "Hours field is required"
                    },
                    timeline_desc: {
                        required: "Description must not empty"
                    }
                },
                submitHandler: function(form) {
                    console.log(form);
                    //form.submit();
                }
                
               });
        });



                       
});

$(document).ready(function(){
     $('.ChooseType').change(function(){
        // alert($('.ChooseType:checked').val());
        var val_leave = $('.ChooseType:checked').val();
        if(val_leave == 'personal'){
            $('#Personal_leaves').css('display','');
            $('#team_leaves').css('display','none');
        }
        if(val_leave == 'team'){
            $('#Personal_leaves').css('display','none');
            $('#team_leaves').css('display','');
        }
     });
});

function check_email_reporter()
{
    var u_email = $('#reporter_email').val();
    if(u_email != '')
    {
         $.post(base_url+'settings/check_reporter_mail/',{user_email:u_email},function(res){
                        if(res == 'new'){
                            $('#reporter_email_error_exist').css('display','');
                            $('#lead_reporter_add').attr('disabled','disabled')
                            return false;
                        }else{
                            $('#reporter_email_error_exist').css('display','none');
                            $('#lead_reporter_add').removeAttr('disabled','')
                        }
                    });
    }

}

$('#Add_more_institution').click(function() {
    var add_div = '<div class="card-box MultipleInstitutions"><h3 class="card-title">Education Informations</h3> <a href="#" class="remove_div"><i class="fa fa-trash-o"></i></a><div class="row"><div class="col-md-6"><div class="form-group form-focus focused"><input type="text" value="" class="form-control floating" name="institute[]"><label class="control-label">Institution</label></div></div><div class="col-md-6"><div class="form-group form-focus focused"><input type="text" class="form-control floating" name="subject[]"><label class="control-label">Subject</label></div></div><div class="col-md-6"><div class="form-group form-focus focused"><div class="cal-icon"><input type="text" name="start_date[]" class="form-control floating datetimepicker"></div><label class="control-label">Starting Date</label></div></div><div class="col-md-6"><div class="form-group form-focus focused"><div class="cal-icon"><input type="text" name="end_date[]" class="form-control floating datetimepicker"></div><label class="control-label">Complete Date</label></div></div><div class="col-md-6"><div class="form-group form-focus focused"><input type="text" name="degree[]" class="form-control floating"><label class="control-label">Degree</label></div></div><div class="col-md-6"><div class="form-group form-focus focused"><input type="text" name="grade[]" class="form-control floating"><label class="control-label">Grade</label></div></div></div></div>';
    $('.MultipleInstitutions:last').after(add_div);
});
$('.AllInstitute').on('click','.remove_div',function() {
    $(this).parent().remove();
});

$('#Add_experience').click(function() {
    var add_div = '<div class="MultipleExperience"><h3 class="card-title">Experience Informations </h3> <a href="#" class="remove_exp_div"><i class="fa fa-trash-o"></i></a><div class="row"><div class="col-md-6"><div class="form-group form-focus"><input type="text" class="form-control floating" value="" name="company_name[]"><label class="control-label">Company Name</label></div></div><div class="col-md-6"><div class="form-group form-focus"><input type="text" class="form-control floating" value="" name="location[]"><label class="control-label">Location</label></div></div><div class="col-md-6"><div class="form-group form-focus"><input type="text" class="form-control floating" value="" name="job_position[]"><label class="control-label">Job Position</label></div></div><div class="col-md-6"><div class="form-group form-focus"><div class="cal-icon"><input type="text" class="form-control floating datetimepicker" value="" name="period_from[]"></div><label class="control-label">Period From</label></div></div><div class="col-md-6"><div class="form-group form-focus"><div class="cal-icon"><input type="text" class="form-control floating datetimepicker" value="" name="period_to[]"></div><label class="control-label">Period To</label></div></div></div></div>';
    $('.MultipleExperience:last').after(add_div);
});
$('.AllExperience').on('click','.remove_exp_div',function() {
    $(this).parent().remove();
});


$('#add_more_family').click(function() {
    var add_div = '<div class="FamilyMembers"><h3 class="card-title">Family Member </h3> <a href="#" class="remove_family_div"><i class="fa fa-trash-o"></i></a><div class="row"><div class="col-md-6"><div class="form-group"><label>Name <span class="text-danger">*</span></label><input class="form-control" type="text" name="member_name[]" placeholder="Name"></div></div><div class="col-md-6"><div class="form-group"><label>Relationship <span class="text-danger">*</span></label><input class="form-control" type="text" name="member_relationship[]" placeholder ="Relationship"></div></div><div class="col-md-6"><div class="form-group"><label>Date of birth <span class="text-danger">*</span></label><input class="form-control ALlmembers" type="text" name="member_dob[]"></div></div><div class="col-md-6"><div class="form-group"><label>Phone <span class="text-danger">*</span></label><input class="form-control" type="text" name="member_phone[]" placeholder="Phone Number"></div></div></div></div>';
    $('.FamilyMembers:last').after(add_div);
});
$('.AllFamilyMembers').on('click','.remove_family_div',function() {
    $(this).parent().remove();
});






$('#tokbox_set_btn').click(function(){
    var apikey = $('#apikey_tokbox').val();
    var apikeysecret = $('#apisecret_tokbox').val();
    if(apikey == '')
    {
        toastr.error('Apikey Field is Required');
        return false;
    }
    if(apikeysecret == '')
    {
        toastr.error('ApikeySecret Field is Required');
        return false;
    }
});


$(document).ready(function(){
     $('.TaskType').change(function(){
        // alert($('.ChooseType:checked').val());
        var val_leave = $('.TaskType:checked').val();
        if(val_leave == 'all'){
            $('.AllTasks').css('display','');
            $('.PendingTasks').css('display','none');
            $('.CompleteTasks').css('display','none');
            $('.MyTasks').css('display','none');
        }
        if(val_leave == 'pending'){
            $('.PendingTasks').css('display','');
            $('.AllTasks').css('display','none');
            $('.CompleteTasks').css('display','none');
            $('.MyTasks').css('display','none');
        }
        if(val_leave == 'complete'){
            $('.CompleteTasks').css('display','');
            $('.AllTasks').css('display','none');
            $('.PendingTasks').css('display','none');
            $('.MyTasks').css('display','none');
        }
        if(val_leave == 'my_task'){
            $('.MyTasks').css('display','');
            $('.CompleteTasks').css('display','none');
            $('.AllTasks').css('display','none');
            $('.PendingTasks').css('display','none');
        }
     });
});

$(document).ready(function(){
    $('#purchase_date').datepicker();
    $('#dob_edit').datepicker();
    $('#passport_expiry').datepicker();
    $('#warranty_date').datepicker();
    $("#add_asset_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                asset_name: {
                    required: true
                },
                reference_id: {
                    required: true
                },
                purchase_date: {
                    required: true
                },
                purchase_from: {
                    required: true
                },
                manufacture: {
                    required: true
                },
                model: {
                    required: true
                },
                serial_number: {
                    required: true
                },
                supplier: {
                    required: true
                },
                asset_condition: {
                    required: true
                },
                warranty_date: {
                    required: true
                },
                assets_value: {
                    required: true
                },
                asset_user: {
                    required: true
                },
                description: {
                    required: true
                },
                status: {
                    required: true
                }
            },
            messages: {
                asset_name: {
                    required: "Asset Name must not be empty"
                },
                reference_id: {
                    required: "Reference ID must not be empty"
                },
                purchase_date: {
                    required: "Purchase Date must not be empty"
                },
                purchase_from: {
                    required: "Purchase from must not be empty"
                },
                manufacture: {
                    required: "Manufacture must not be empty"
                },
                model: {
                    required: "Model must not be empty"
                },
                serial_number: {
                    required: "Serial Number must not be empty"
                },
                supplier: {
                    required: "Supplier must not be empty"
                },
                asset_condition: {
                    required: "Condition must not be empty"
                },
                warranty_date: {
                    required: "Warranty Date must not be empty"
                },
                assets_value: {
                    required: "Asset Value must not be empty"
                },
                asset_user: {
                    required: "Please choose any one User"
                },
                description: {
                    required: "Description must not be empty"
                },
                status: {
                    required: "Status must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });


    $("#edit_assets_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                asset_name: {
                    required: true
                },
                reference_id: {
                    required: true
                },
                purchase_date: {
                    required: true
                },
                purchase_from: {
                    required: true
                },
                manufacture: {
                    required: true
                },
                model: {
                    required: true
                },
                serial_number: {
                    required: true
                },
                supplier: {
                    required: true
                },
                asset_condition: {
                    required: true
                },
                warranty_date: {
                    required: true
                },
                assets_value: {
                    required: true
                },
                asset_user: {
                    required: true
                },
                description: {
                    required: true
                },
                status: {
                    required: true
                }
            },
            messages: {
                asset_name: {
                    required: "Asset Name must not be empty"
                },
                reference_id: {
                    required: "Reference ID must not be empty"
                },
                purchase_date: {
                    required: "Purchase Date must not be empty"
                },
                purchase_from: {
                    required: "Purchase from must not be empty"
                },
                manufacture: {
                    required: "Manufacture must not be empty"
                },
                model: {
                    required: "Model must not be empty"
                },
                serial_number: {
                    required: "Serial Number must not be empty"
                },
                supplier: {
                    required: "Supplier must not be empty"
                },
                asset_condition: {
                    required: "Condition must not be empty"
                },
                warranty_date: {
                    required: "Warranty Date must not be empty"
                },
                assets_value: {
                    required: "Asset Value must not be empty"
                },
                asset_user: {
                    required: "Please choose any one User"
                },
                description: {
                    required: "Description must not be empty"
                },
                status: {
                    required: "Status must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });



    $("#basic_info_form1").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                full_name: {
                    required: true
                },
                dob: {
                    required: true
                },
                gender: {
                    required: true
                },
                address: {
                    required: true
                },
                state: {
                    required: true
                },
                country: {
                    required: true
                },
                pincode: {
                    required: true
                },
                phone: {
                    required: true
                }
            },
            messages: {
                full_name: {
                    required: "Full Name must not be empty"
                },
                dob: {
                    required: "DOB must not be empty"
                },
                gender: {
                    required: "Gender must not be empty"
                },
                address: {
                    required: "Address must not be empty"
                },
                state: {
                    required: "State must not be empty"
                },
                country: {
                    required: "Country must not be empty"
                },
                pincode: {
                    required: "Pincode must not be empty"
                },
                phone: {
                    required: "Phone must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });



    $("#personal_info_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                passport_no: {
                    required: true
                },
                passport_expiry: {
                    required: true
                },
                tel_number: {
                    required: true
                },
                nationality: {
                    required: true
                },
                religion: {
                    required: true
                },
                marital_status: {
                    required: true
                }
            },
            messages: {
                passport_no: {
                    required: "Passport Number must not be empty"
                },
                passport_expiry: {
                    required: "Expiry must not be empty"
                },
                tel_number: {
                    required: "Tel Number must not be empty"
                },
                nationality: {
                    required: "Nationality must not be empty"
                },
                religion: {
                    required: "Full Name must not be empty"
                },
                marital_status: {
                    required: "Marital Status must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });


    $("#emergency_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                contact_name1: {
                    required: true
                },
                relationship1: {
                    required: true
                },
                contact1_phone1: {
                    required: true
                },
                contact_name2: {
                    required: true
                },
                relationship2: {
                    required: true
                },
                contact2_phone1: {
                    required: true
                }
            },
            messages: {
                contact_name1: {
                    required: "Contact Name must not be empty"
                },
                relationship1: {
                    required: "Relationship must not be empty"
                },
                contact1_phone1: {
                    required: "Contact Phone Number must not be empty"
                },
                contact_name2: {
                    required: "Contact Name must not be empty"
                },
                relationship2: {
                    required: "Relationship must not be empty"
                },
                contact2_phone1: {
                    required: "Contact Phone Number must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });


    $("#bank_info_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                bank_name: {
                    required: true
                },
                bank_ac_no: {
                    required: true
                },
                ifsc_code: {
                    required: true
                },
                pan_no: {
                    required: true
                }
            },
            messages: {
                bank_name: {
                    required: "Bank Name must not be empty"
                },
                bank_ac_no: {
                    required: "Account Number must not be empty"
                },
                ifsc_code: {
                    required: "IFSC Code must not be empty"
                },
                pan_no: {
                    required: "Pan Number must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });


    $("#newtype_leave_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                leave_type_name: {
                    required: true
                },
                leave_days: {
                    required: true
                }
            },
            messages: {
                leave_type_name: {
                    required: "Leave Type Name must not be empty"
                },
                leave_days: {
                    required: "Leave Days must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
$(document).on('click',".ALlmembers",function() {
    $('.ALlmembers').datepicker();
});
    $('.ALlmembers').datepicker();
    $("#family_info_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                'member_name[]': {
                    required: true
                },
                'member_relationship[]': {
                    required: true
                },
                'member_dob[]': {
                    required: true
                },
                'member_phone[]': {
                    required: true
                }
            },
            messages: {
                'member_name[]': {
                    required: "Name must not be empty"
                },
                'member_relationship[]': {
                    required: "Relationship must not be empty"
                },
                'member_dob[]': {
                    required: "DOB must not be empty"
                },
                'member_phone[]': {
                    required: "Phone Number must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });


$(document).on('click',".datetimepicker",function() {
    $('.datetimepicker').datepicker();
});
    $('.datetimepicker').datepicker();


    
    $("#education_info_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                'institute[]': {
                    required: true
                },
                'subject[]': {
                    required: true
                },
                'start_date[]': {
                    required: true
                },
                'end_date[]': {
                    required: true
                },
                'degree[]': {
                    required: true
                },
                'grade[]': {
                    required: true
                }
            },
            messages: {
                'institute[]': {
                    required: "Institute Name must not be empty"
                },
                'subject[]': {
                    required: "Subject must not be empty"
                },
                'start_date[]': {
                    required: "Start Date must not be empty"
                },
                'end_date[]': {
                    required: "End Date must not be empty"
                },
                'degree[]': {
                    required: "Degree must not be empty"
                },
                'grade[]': {
                    required: "Grade must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
           
           
           $(document).on('click',".datetimepicker",function() {
                $('.datetimepicker').datepicker();
            });
           $('.datetimepicker').datepicker();


    
    $("#experience_info_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                'company_name[]': {
                    required: true
                },
                'location[]': {
                    required: true
                },
                'job_position[]': {
                    required: true
                },
                'period_from[]': {
                    required: true
                },
                'period_to[]': {
                    required: true
                }
            },
            messages: {
                'company_name[]': {
                    required: "Company Name must not be empty"
                },
                'location[]': {
                    required: "Location must not be empty"
                },
                'job_position[]': {
                    required: "Job Position must not be empty"
                },
                'period_from[]': {
                    required: "Period From Date must not be empty"
                },
                'period_to[]': {
                    required: "Period To Date must not be empty"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
            
           });
});

$(document).ready(function(){
    $('.BtnEdit').click(function(){
        $('.UpdateBtn').css('display','block');
        $('.EditBtn').css('display','none');
        $('.Daystext').removeAttr('disabled');
    });
    $('.CancelBtn').click(function(){
        $('.EditBtn').css('display','block');
        $('.UpdateBtn').css('display','none');
        $('.Daystext').attr('disabled','disabled');
    });
    $('.CarryFwd').change(function(){
        var carryfwd = $(this).val();
        if(carryfwd == 'yes')
        {
            $('#MaxDays').css('display','block');
        }else{
            $('#MaxDays').css('display','none');
        }
    });
    $('.EditMax').click(function(){
        $('.CarryFwd').removeAttr('disabled');
        $('#MaxDays').removeAttr('disabled');
        $('.UpdateMaxBtn').css('display','block');
        $('.EditMax').css('display','none');
    });
    $('.CancelMaxBtn').click(function(){
        $('.CarryFwd').attr('disabled','disabled');
        $('#MaxDays').attr('disabled','disabled');
        $('.UpdateMaxBtn').css('display','none');
        $('.EditMax').css('display','block');
    });
    

    $(document).on('click',"#annual",function() {
        var annual_leaves = $('#annual_leaves').val();
        if(annual_leaves == '')
        {
            toastr.error('Annual Leaves Field is Required');
            return false;
        }else{
            $.post(base_url + 'leave_settings/update_annual_leaves/', { annual_leaves: annual_leaves}, function (datas) {
                // console.log(datas); return false;
               $('#annual_leaves').val(datas);
               toastr.success('Annual Leaves Updated');
               setTimeout(function () {
                    location.reload();
                }, 1500);
            });
        }
    });
    
    $(document).on('click',"#sick",function() {
        var sick_leave = $('#sick_leave').val();
        if(sick_leave == '')
        {
            toastr.error('Sick Leaves Field is Required');
            return false;
        }else{
            $.post(base_url + 'leave_settings/update_sick_leave/', { sick_leave: sick_leave}, function (datas) {
               $('#sick_leave').val(datas);
               toastr.success('Sick Leaves Updated');
               setTimeout(function () {
                    location.reload();
                }, 1500);
            });
        }
    });
    
    $(document).on('click',"#hospitalisation",function() {
        var hospitalisation = $('#hospitalisation').val();
        if(hospitalisation == '')
        {
            toastr.error('Hospitalisation Leaves Field is Required');
            return false;
        }else{
            $.post(base_url + 'leave_settings/update_hospitalisation_leave/', { hospitalisation: hospitalisation}, function (datas) {
               $('#hospitalisation').val(datas);
               toastr.success('Hospitalisation Leaves Updated');
               setTimeout(function () {
                    location.reload();
                }, 1500);
            });
        }
    });
    
    $(document).on('click',"#maternity",function() {
        var maternity = $('#maternity_leaves').val();
        if(maternity == '')
        {
            toastr.error('Maternity Leaves Field is Required');
            return false;
        }else{
            $.post(base_url + 'leave_settings/update_maternity_leave/', { maternity: maternity}, function (datas) {
               $('#maternity_leaves').val(datas);
               toastr.success('Maternity Leaves Updated');
               setTimeout(function () {
                    location.reload();
                }, 1500);
            });
        }
    });
    
    $(document).on('click',"#paternity",function() {
        var paternity = $('#paternity_leaves').val();
        if(paternity == '')
        {
            toastr.error('Paternity Leaves Field is Required');
            return false;
        }else{
            $.post(base_url + 'leave_settings/update_paternity_leave/', { paternity: paternity}, function (datas) {
               $('#paternity_leaves').val(datas);
               toastr.success('Paternity Leaves Updated');
               setTimeout(function () {
                    location.reload();
                }, 1500);
            });
        }
    });
    
    $(document).on('click',"#carry_forward",function() {
        var carry_max = $('#carry_max').val();
        var leave_status = $('.CarryFwd:checked').val();
        // alert(leave_status);
        if(leave_status == 'yes')
        {
            if(carry_max == '')
            {
                toastr.error('Carry Forward Leaves Field is Required');
                return false;
            }
        }
                $.post(base_url + 'leave_settings/update_carry_forward_leave/', { carry_max: carry_max,leave_status:leave_status}, function (datas) {
                   $('#carry_max').val(datas);
                   toastr.success('Carry Forward Leaves Updated');
                   setTimeout(function () {
                        location.reload();
                    }, 1500);
                });
    });
    
    $(document).on('click',"#earned",function() {
        var earned_leaves = $('#earned_leaves').val();
        var leave_status = $('.earnLeaves:checked').val();
        if(leave_status == 'yes')
        {
            if(earned_leaves == '')
            {
                toastr.error('Carry Forward Leaves Field is Required');
                return false;
            }
        }
                $.post(base_url + 'leave_settings/update_earned_leave/', { earned_leaves: earned_leaves,leave_status:leave_status}, function (datas) {
                   $('#earned_leaves').val(datas);
                   toastr.success('Carry Forward Leaves Updated');
                   setTimeout(function () {
                        location.reload();
                    }, 1500);
                });
    });
    $(document).on('click',".PolicyID",function() {
        var policy_id = $(this).data('id');
        $('#policy_id').val(policy_id);
    });
    $(document).on('click',".PolicyBtn",function() {
        var policy_name = $('#policy_name').val();
        // alert(policy_name); 
        var policy_days = $('#policy_days').val();
        var policy_id = $('#policy_id').val();
        var options = $('#customleave_select_to option');
        var users = $.map(options ,function(option) {
            return option.value;
        });
        if(policy_name == '')
        {
            toastr.error('Policy Name is Required');
            return false;
        }
        if(policy_days == '')
        {
            toastr.error('Policy Days is Required');
            return false;
        }
        if(users.length == 0)
        {
            toastr.error('Please choose atleast one Employee');
            return false;   
        }
        console.log(users);

        $.post(base_url + 'leave_settings/add_custom_policy/', { policy_name: policy_name,policy_days:policy_days,users:users,policy_id:policy_id}, function (datas) {
            // console.log(datas); 
            toastr.success('Custom Policy Leaves Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
        });

    });
    $(document).on('click',".update_policy_user",function() {
        var policy_name = $('#policy_name').val();
        // alert(policy_name); 
        var policy_days = $('#policy_days').val();
        var policy_id = $(this).data('id');
        var options = $('.EditSelectUsers option');
        var users = $.map(options ,function(option) {
            return option.value;
        });
        if(policy_name == '')
        {
            toastr.error('Policy Name is Required');
            return false;
        }
        if(policy_days == '')
        {
            toastr.error('Policy Days is Required');
            return false;
        }
        if(users.length == 0)
        {
            toastr.error('Please choose atleast one Employee');
            return false;   
        }
        console.log(users);

        $.post(base_url + 'leave_settings/update_policy_user/', { policy_name: policy_name,policy_days:policy_days,users:users,policy_id:policy_id}, function (datas) {
            console.log(datas); return false;
            toastr.success('Custom Policy Leaves Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
        });

    });

    $(document).on('change','#annual_switch',function(){
        var policy_id = $(this).data('id');
       $.post(base_url + 'leave_settings/change_status/', {policy_id:policy_id}, function (datas) {
        console.log(datas);
        toastr.success('Status Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
       });
    });

    $(document).on('change','#switch_sick',function(){
        var policy_id = $(this).data('id');
        // alert(policy_id);
       $.post(base_url + 'leave_settings/change_status/', {policy_id:policy_id}, function (datas) {
        console.log(datas);
        toastr.success('Status Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
       });
    });

    $(document).on('change','#switch_hospitalisation',function(){
        var policy_id = $(this).data('id');
        // alert(policy_id);
       $.post(base_url + 'leave_settings/change_status/', {policy_id:policy_id}, function (datas) {
        console.log(datas);
        toastr.success('Status Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
       });
    });

    $(document).on('change','#switch_maternity',function(){
        var policy_id = $(this).data('id');
        // alert(policy_id);
       $.post(base_url + 'leave_settings/change_status/', {policy_id:policy_id}, function (datas) {
        console.log(datas);
        toastr.success('Status Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
       });
    });

    $(document).on('change','#switch_paternity',function(){
        var policy_id = $(this).data('id');
        // alert(policy_id);
       $.post(base_url + 'leave_settings/change_status/', {policy_id:policy_id}, function (datas) {
        console.log(datas);
        toastr.success('Status Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
       });
    });

    $(document).on('change','.ALLExtraSwitch',function(){
        var policy_id = $(this).data('id');
        // alert(policy_id);
       $.post(base_url + 'leave_settings/change_status/', {policy_id:policy_id}, function (datas) {
        console.log(datas);
        toastr.success('Status Updated');
           setTimeout(function () {
                location.reload();
            }, 1500);
       });
    });
    $(document).on('change','#employee_pro_pics',function(e){
        var file_data = $('#employee_pro_pics').prop('files')[0];
        var user_id = $('#employee_user_id').val();
        var form_data = new FormData();                  
        form_data.append('file', file_data);
        form_data.append('user_id', user_id);

    $.ajax({  
                    url:base_url +'employees/employee_profile_upload/',
                    dataType: 'text',  // what to expect back from the PHP script, if anything
                    cache: false,
                    contentType: false,
                    processData: false,
                    data: form_data,                         
                    type: 'post',
                     success:function(data)  
                     {  
                          if(data == 'success')
                          {
                            toastr.success('Profile Image Updated');
                               setTimeout(function () {
                                    location.reload();
                                }, 1500);
                           }else{
                            toastr.error('Upload Failed');
                               setTimeout(function () {
                                    location.reload();
                                }, 1500);
                           }
                     }  
                });  
    });


    $('.leave-delete-btn').click(function(){
        var leave_id = $(this).data('id');
        toastr.info("<button type='button' id='confirmationRevertYes' class='btn clear'>Yes</button>",'Sure to delete LeaveType?',
          {
              closeButton: false,
              allowHtml: true,
              onShown: function (toast) {
                  $("#confirmationRevertYes").click(function(){
                    console.log(leave_id);
                    $.post(base_url + 'leave_settings/delete_newleave_types/', {leave_id:leave_id}, function (datas) {
                        toastr.success('Deleted');
                               setTimeout(function () {
                                    location.reload();
                                }, 1000);
                    });
                  });
                }
          });
    });
});

$(document).ready(function(){

    
    $(document).on('click','.DeleteAddtion',function(){
        var arid = $(this).data('arid');
        var user_id = $(this).data('user');
        var keyid = $(this).data('keyid');
        toastr.info("<button type='button' id='confirmationRevertYes' class='btn clear'>Yes</button>",'Sure to delete PF Addtion?',
          {
              closeButton: false,
              allowHtml: true,
              onShown: function (toast) {
                  $("#confirmationRevertYes").click(function(){
                    // console.log(leave_id);
                    $.post(base_url + 'employees/delete_pfaddtional/', {arid:arid,user_id:user_id,keyid:keyid}, function (datas) {
                        toastr.success('Deleted');
                               setTimeout(function () {
                                    location.reload();
                                }, 1000);
                    });
                  });
                }
          });
        
    });

$(document).on('click','.DeleteDeduction',function(){
        var arid = $(this).data('arid');
        var user_id = $(this).data('user');
        var keyid = $(this).data('keyid');
        toastr.info("<button type='button' id='confirmationRevertYes' class='btn clear'>Yes</button>",'Sure to delete PF Addtion?',
          {
              closeButton: false,
              allowHtml: true,
              onShown: function (toast) {
                  $("#confirmationRevertYes").click(function(){
                    // console.log(leave_id);
                    $.post(base_url + 'employees/delete_pfdeduction/', {arid:arid,user_id:user_id,keyid:keyid}, function (datas) {
                        toastr.success('Deleted');
                               setTimeout(function () {
                                    location.reload();
                                }, 1000);
                    });
                  });
                }
          });
        
    });


    
    $('#pf_contribution').change(function(){
        // alert($(this).val());
        var pf_cont = $(this).val();
        if(pf_cont == 'no')
        {
            $(".PFrecords").each(function() {
                $(this).attr('disabled','disabled');
            });
        }else{
            $(".PFrecords").each(function() {
                $(this).removeAttr('disabled');
            });
        }
    });

    $('#esi_contribution').change(function(){
        // alert($(this).val());
        var pf_cont = $(this).val();
        if(pf_cont == 'no')
        {
            $(".ESIrecords").each(function() {
                $(this).attr('disabled','disabled');
            });
        }else{
            $(".ESIrecords").each(function() {
                $(this).removeAttr('disabled');
            });
        }
    });

    $('#pf_rates').change(function(){
        // alert($(this).val());
        var pf_cont = $(this).val();
        if(pf_cont == 'no')
        {
            $('#pf_add_rates').val('');
            $('#pf_total_rate').val('');
            $(".EMprate").each(function() {
                $(this).attr('disabled','disabled');
            });
        }else{
            $(".EMprate").each(function() {
                $(this).removeAttr('disabled');
            });
        }
    });

    $('#pf_employer_contribution').change(function(){
        // alert($(this).val());
        var pf_cont = $(this).val();
        if(pf_cont == 'no')
        {
            $('#employer_add_rates').val('');
            $('#employer_total_rates').val('');
            $(".EmprRate").each(function() {
                $(this).attr('disabled','disabled');
            });
        }else{
            $(".EmprRate").each(function() {
                $(this).removeAttr('disabled');
            });
        }
    });

    $('#esi_rate').change(function(){
        // alert($(this).val());
        var pf_cont = $(this).val();
        if(pf_cont == 'no')
        {
            $('#esi_add_rate').val('');
            $('#esi_total_rate').val('');
            $(".ESIRates").each(function() {
                $(this).attr('disabled','disabled');
            });
        }else{
            $(".ESIRates").each(function() {
                $(this).removeAttr('disabled');
            });
        }
    });
    $(document).on('keyup','#pf_add_rates',function(){
        // alert($(this).val());
        var pf_rate = $(this).val();
        var salary = $('#user_salary').val();
        var pf_amount = 0;
        if(pf_rate != '')
        {
            if(salary != '')
            {
                pf_amount = (salary * pf_rate / 100);
            }else{
                toastr.error('Salary field Required');
                $('#user_salary').focus();
            }
        }
        $('#pf_total_rate').val(pf_amount);
    });


    $(document).on('keyup','#employer_add_rates',function(){
        // alert($(this).val());
        var pf_rate = $(this).val();
        var salary = $('#user_salary').val();
        var pf_amount = 0;
        if(pf_rate != '')
        {
            if(salary != '')
            {
                pf_amount = (salary * pf_rate / 100);
            }else{
                toastr.error('Salary field Required');
                $('#user_salary').focus();
            }
        }
        $('#employer_total_rates').val(pf_amount);
    });


    $(document).on('keyup','#esi_add_rate',function(){
        // alert($(this).val());
        var pf_rate = $(this).val();
        var salary = $('#user_salary').val();
        var pf_amount = 0;
        if(pf_rate != '')
        {
            if(salary != '')
            {
                pf_amount = (salary * pf_rate / 100);
            }else{
                toastr.error('Salary field Required');
                $('#user_salary').focus();
            }
        }
        $('#esi_total_rate').val(pf_amount);
    });


     $("#user_salary,#pf_add_rates,#employer_add_rates,#esi_add_rate,#unit_amount").on("keypress keyup blur",function (event) {
            //this.value = this.value.replace(/[^0-9\.]/g,'');
     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
            if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
                event.preventDefault();
            }
        });


     $(document).on("change", ".BudgetType", function() {
        // alert($(this).val());
        var budget_type = $(this).val();
        if(budget_type == 'project'){
            $('.ProjecTS').css('display','block');
            $('.CategorY').css('display','none');
            $('.SUbCategorY').css('display','none');
        }

        if(budget_type == 'category'){
            $('.ProjecTS').css('display','none');
            $('.CategorY').css('display','block');
            $('.SUbCategorY').css('display','block');
        }

    });




     $(document).on('change','#main_category',function(){
        var category_id = $( "#main_category option:selected" ).val();
        $.post(base_url + 'budgets/check_subcategories/', {category_id:category_id}, function (datas) {
            var sub_categories = JSON.parse(datas);
            $('#sub_category').empty();
            $('#sub_category').append("<option value='' selected disabled='disabled'>Choose Sub-Category</option>");
            for(i=0; i<sub_categories.length; i++) {
                $('#sub_category').append("<option value="+sub_categories[i].cat_id+">"+sub_categories[i].sub_category+"</option>");                      
             }
        });
    });



    $('.add_more_revenue').click(function() {
        var add_div = '<div class="row AlLRevenues"><a class="remove_revenue_div" style="cursor: pointer;"><i class="fa fa-trash-o"></i></a><div class="col-sm-6"><div class="form-group"><label>Revenue Title <span class="text-danger">*</span></label><input type="text" class="form-control RevenuETitle" value="" placeholder="Revenue Title" name="revenue_title[]" autocomplete="off"></div></div><div class="col-sm-5"><div class="form-group"><label>Revenue Amount <span class="text-danger">*</span></label><input type="text" name="revenue_amount[]" placeholder="Amount" value="" class="form-control RevenuEAmount" autocomplete="off"></div></div></div>';
        $('.AlLRevenues:last').after(add_div);
    });
    $('.AllRevenuesClass').on('click','.remove_revenue_div',function() {
        $(this).parent().remove();
        var amount = 0;
        $('.RevenuEAmount').each(function() {
            var revenue_amount = $(this);
            amount += +revenue_amount.val(); 
        });
        $('#overall_revenues').val(amount);
        var ex_amount = 0;
        $('.EXpensesAmount').each(function() {
            var expenses_amount = $(this);
            ex_amount += +expenses_amount.val(); 
        });
        $('#overall_expenses').val(ex_amount);
        var overall_revenue = $('#overall_revenues').val();
        var overall_expenses = $('#overall_expenses').val();
        var total_amount = parseInt(overall_revenue) + parseInt(overall_expenses);
        // alert(total_amount);
        $('#expected_profit').val(total_amount);
    });


    $('.add_more_expenses').click(function() {
        var add_div = '<div class="row AlLExpenses"><a class="remove_expenses_div" style="cursor: pointer;"><i class="fa fa-trash-o"></i></a><div class="col-sm-6"><div class="form-group"><label>Expenses Title <span class="text-danger">*</span></label><input type="text" class="form-control EXpensesTItle" value="" placeholder="Expenses Title" name="expenses_title[]" autocomplete="off"></div></div><div class="col-sm-5"><div class="form-group"><label>Expenses Amount <span class="text-danger">*</span></label><input type="text" name="expenses_amount[]" placeholder="Amount" value="" class="form-control EXpensesAmount" autocomplete="off"></div></div></div>';
        $('.AlLExpenses:last').after(add_div);
    });
    $('.AllExpensesClass').on('click','.remove_expenses_div',function() {
        $(this).parent().remove();
        var amount = 0;
        $('.RevenuEAmount').each(function() {
            var revenue_amount = $(this);
            amount += +revenue_amount.val(); 
        });
        $('#overall_revenues').val(amount);
        var ex_amount = 0;
        $('.EXpensesAmount').each(function() {
            var expenses_amount = $(this);
            ex_amount += +expenses_amount.val(); 
        });
        $('#overall_expenses').val(ex_amount);
        var overall_revenue = $('#overall_revenues').val();
        var overall_expenses = $('#overall_expenses').val();
        var total_amount = parseInt(overall_revenue) - parseInt(overall_expenses);
        // alert(total_amount);
        $('#expected_profit').val(total_amount);
        $('#budget_amount').val(total_amount);
    });

    $(document).on("change", ".RevenuEAmount", function() {
        var amount = 0;
        $('.RevenuEAmount').each(function() {
            var revenue_amount = $(this);
            amount += +revenue_amount.val(); 
        });
        $('#overall_revenues').val(amount);
        var overall_revenue = $('#overall_revenues').val();
        var overall_expenses = $('#overall_expenses').val();
        if(overall_expenses != 0){
            var total_amount = parseInt(overall_revenue) - parseInt(overall_expenses);
            $('#expected_profit').val(total_amount);
            $('#budget_amount').val(total_amount);
        }
    });

    $(document).on("change", ".EXpensesAmount", function() {
        var ex_amount = 0;
        $('.EXpensesAmount').each(function() {
            var expenses_amount = $(this);
            ex_amount += +expenses_amount.val(); 
        });
        $('#overall_expenses').val(ex_amount);
        var overall_revenue = $('#overall_revenues').val();
        var overall_expenses = $('#overall_expenses').val();
        var total_amount = parseInt(overall_revenue) - parseInt(overall_expenses);
        // alert(total_amount);
        $('#expected_profit').val(total_amount);
        $('#budget_amount').val(total_amount);
    });

    $(document).on("keyup", "#tax_amount", function() {
        
        var expected_profit = $('#expected_profit').val();
        var tax_amount = $('#tax_amount').val();
        var total_amount = parseInt(expected_profit) - parseInt(tax_amount);
        $('#budget_amount').val(total_amount);
    });


    $("#add_budget_form").validate({
            onsubmit: true,
            ignore: [] ,
            rules: {
                budget_title: {
                    required: true
                },
                budget_start_date: {
                    required: true
                },
                budget_end_date: {
                    required: true
                }
            },
            messages: {
                budget_title: {
                    required: "Budget Title is Required"
                },
                budget_start_date: {
                    required: "Budget Start Date is Required"
                },
                budget_end_date: {
                    required: "Budget Start Date is Required"
                }
            },
            submitHandler: function(form) {
                form.submit();
            }
           });


    $(".EXpensesAmount").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message     
               return false;
    }
   });

    $(".RevenuEAmount").keypress(function (e) {
     //if the letter is not digit then display error and don't type anything
     if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
        //display error message
               return false;
    }
   });


    $(document).on('click','.menu-icon',function(){
        $('#site-icon').iconpicker({hideOnSelect: true, placement: 'bottomLeft'});
            $('.menu-icon').iconpicker().on('iconpickerSelected',function(event){
                var role = $(this).attr('data-role');
                var target = $(this).attr('data-href');
                $(this).siblings('div.iconpicker-container').hide();
                $.ajax({
                    url: target,
                    type: 'POST',
                    data: { icon: event.iconpickerValue, access: role  },
                    success: function() {},
                    error: function(xhr) {}
                });
            });
    });
        $(document).on('change','#select_roles',function(){

            var role = $( "#select_roles option:selected" ).val();
            var role_name = role.replace("_", " ");
            $('.LoadGiFImg').css('display','block');
            // alert(role);
            $.post(base_url + 'settings/getMenu_role/', {role:role}, function (datas) {
                data = JSON.parse(datas);
                console.log(data);
                var htmlbody = '';
                var recordscount = data.length;

                htmlbody = '<div class=" fade in"><h2>'+role_name+'</h2><div class="table-responsive"><table id="menu-admin" class="table table-striped table-bordered custom-table m-b-0 sorted_table"><thead><tr><th></th><th class="col-xs-2">Icon</th><th class="col-xs-8">Menu</th><th class="col-xs-2 text-center">Visible</th></tr></thead><tbody>';
                for (var i = 0; i < recordscount; i++) {
                    var record = data[i];
                    var record_visible;
                    if(record.visible == 1)
                    {
                        record_visible = "success";
                    }else{
                        record_visible = "default";
                    }
                    htmlbody += '<tr class="sortable" data-module="'+record.module+'" data-access="1"><td class="drag-handle"><i class="fa fa-reorder"></i></td><td><div class="btn-group"><button class="btn btn-default iconpicker-component" type="button"><i class="fa '+record.icon+' fa-fw"></i></button><button data-toggle="dropdown" data-selected="'+record.icon+'" class="menu-icon icp icp-dd btn btn-default dropdown-toggle" type="button" aria-expanded="false" data-role="'+record.access+'" data-href="'+base_url+'settings/hook/icon/'+record.module+'"><span class="caret"></span></button><div class="dropdown-menu iconpicker-container"></div></div></td><td>'+record.name+'</td><td class="text-center"><a data-rel="tooltip" data-original-title="Toggle" class="menu-view-toggle btn btn-xs btn-'+record_visible+'" href="#" data-role="'+record.access+'" data-href="'+base_url+'settings/hook/visible/'+record.module+'"><i class="fa fa-eye"></i></a></td></tr>';
                }

                $('.MenuListRole').html(htmlbody);
                 setTimeout(function () {
                        $('.LoadGiFImg').css('display','none');
                    }, 5000);
                // console.log(data); return false;
            });

            // $('.AllRoleMenu').css('display','none');

            // $('#'+role).css('display','block');

    });

    if($('#role_menu_select').length > 0) {
        $('#role_menu_select').multiselect();
    }

    
});
