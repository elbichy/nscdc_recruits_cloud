$(document).ready(function() {

    //////////////////////////////////// MATERIALIZE & PLUGINS INITS //////////////////////////////////////////
    $('.sidenav').sidenav();
    $('.collapsible').collapsible();
    $('.tooltipped').tooltip();
    $('.modal').modal({
        dismissible: true
    });



    $('.materialboxed').materialbox();
    $('select').formSelect();
    $('#selectBranch').formSelect();
    $('.nav-wrapper .dropdown-content li').click(function(e) {
        $('form.switchBranch').submit();
    });
    $('.timepicker').timepicker({
        defaultTime: '9:00',
        showClearBtn: true
    });
    $('.tabs').tabs({
        swipeable: false
    });
    $('.dropdown-trigger').dropdown();


    // STATIC NAVIGATIONS
    $('#home').click(function() {
        $('html, body').animate({
            scrollTop: $('.header').offset().top
        }, 1000, 'easeOutBack');
    });
    $('#howtoapply').click(function() {
        $('html, body').animate({
            scrollTop: $('#instruction').offset().top
        }, 1000, 'easeOutBack');
    });
    $('#signinlink').click(function() {
        $('html, body').animate({
            scrollTop: $('#formArea').offset().top
        }, 1000, 'easeOutBack');
    });
    $('#howtoapplyBtn').click(function() {
        $('html, body').animate({
            scrollTop: $('#instruction').offset().top
        }, 1000, 'easeOutBack');
    });
    $('#signinlinkBtn').click(function() {
        $('html, body').animate({
            scrollTop: $('#formArea').offset().top
        }, 1000, 'easeOutBack');
    });


    $('.newInfo > button').click(function() {
        $('.existingWrap').hide('fade', function() {
            $('.existingContainer').css({
                'background': 'linear-gradient(45deg, #164f6b 10%, #039be5 90%)'
            })
            $('.existingInfoWrap').show('fade', function() {
                // This is where i reveal reg form
                $('.newInfoWrap').hide('fade', function() {
                    $('.newContainer').css({
                        'background-image': 'none',
                        'background': 'white'
                    })
                    $('.newWrap').show('fade')
                })
            })
        })
    });

    $('.existingInfo > button').click(function() {
        $('.newWrap').hide('fade', function() {
            $('.newContainer').css({
                'background': 'linear-gradient(45deg, #164f6b 10%, #039be5 90%)'
            })
            $('.newInfoWrap').show('fade', function() {
                // This is where i reveal reg form
                $('.existingInfoWrap').hide('fade', function() {
                    $('.existingContainer').css({
                        'background-image': 'none',
                        'background': 'white'
                    })
                    $('.existingWrap').show('fade')
                })
            })
        })
    });

    $('#register_form').submit(function() {
        $('#regButt').fadeOut(function() {
            $('.preloader-wrapper').fadeIn();
        });

    });

    $('#signin_form').submit(function() {
        $('#loginButt').prop('disabled', true);
        $('.progress').fadeIn();
    });

    $('#resetForm').submit(function() {
        $('.resetBtn').prop('disabled', true);
        $('.progress').fadeIn();
    });

    // GET RANKS IN A CADRE
    $('#cadre').change(function() {
        let cadreSelected = $(this).val();
        axios.get(`${base_url}/api/v1/get-ranks/${cadreSelected}`)
            .then(function(response) {
                // console.log(response.data);
                let ranks = response.data;
                $('#rank').html('<option value="" disabled selected>Choose your option</option>');
                ranks.map(function(rank) {
                    $(`
                        <option value="${rank.id}">${rank.name}</option>
                    `).appendTo('#rank');
                })
            })
            .catch(function(error) {
                // handle error
                console.log(error.data);
            })
            .finally(function() {
                // always executed
            });
    });

    // LOAD LGAs AFTER SELECTING STATE OF ORIGIN
    $('#soo').change(function() {
        let stateSelected = $(this).val();
        // GET ALL LOCAL GOVERNMENT AREAS IN NIGERIA
        axios.get(`${base_url}/api/v1/get-lgoo/${stateSelected}`)
            .then(function(response) {
                // console.log(response.data);
                let lgaArray = response.data;
                $('#lga').html('<option value="" disabled selected>Choose your option</option>');
                lgaArray.map(function(lga) {
                    $(`<option value="${lga.id}">${lga.lg_name}</option>`).appendTo('#lga');
                });
            })
            .catch(function(error) {
                // handle error
                console.log(error.data);
            })
            .finally(function() {
                // always executed
            });
    });

    // LOAD LGAs AFTER SELECTING STATE OF RESIDENCE
    $('#sor').change(function() {
        let stateSelected = $(this).val();
        // GET ALL LOCAL GOVERNMENT AREAS IN NIGERIA
        axios.get(`${base_url}/api/v1/get-lgor/${stateSelected}`)
            .then(function(response) {
                // console.log(response.data.data);
                let lgaArray = response.data;
                $('#lgor').html('<option value="" disabled selected>Choose your option</option>');
                lgaArray.map(function(lga) {
                    $(`<option value="${lga.id}">${lga.lg_name}</option>`).appendTo('#lgor');
                });
            })
            .catch(function(error) {
                // handle error
                console.log(error.data);
            })
            .finally(function() {
                // always executed
            });
    });

    $('.proceedBtn').click(function() {
        $(this).hide('fade');
    })

    // ADD NEW PRIMARY SCHOOL RECORD ASYNC
    $('#priForm').submit(function(e) {
        e.preventDefault();
        $('.dummyRow > td').html(`
            <div class="progress">
                <div class="indeterminate"></div>
            </div>
        `);
        let formData = $(this).serialize();
        let url = `${base_url}applicant/primary-school/store`;
        console.log(formData);
        console.log(url);
        axios.post(url, formData)
            .then(function(response) {
                $('.dummyRow').fadeOut();
                // console.log(response.data);
                $('#priForm')[0].reset();
                response.data.count > 0 ? $('.primaryProceed').removeAttr('disabled') : '';
                $(`
                    <tr>
                        <td>${response.data.data.nameOfSchool.charAt(0).toUpperCase() + response.data.data.nameOfSchool.slice(1)}, ${response.data.data.location.charAt(0).toUpperCase() + response.data.data.location.slice(1)}</td>

                        <td>${response.data.data.certType.toUpperCase()}</td>
                        <td>${response.data.data.priStartYear}</td>
                        <td>${response.data.data.priEndYear}</td>
                        <td>
                            <a href="#" onclick="deletePrimary(event)" id="deleteRow" data-row_id="${response.data.data.id}" class="red-text">x</a>
                        </td>
                    </tr>
                `).appendTo('.priRows');
                $.wnoty({
                    type: 'success',
                    message: response.data.success,
                    autohideDelay: 5000
                });
            })
            .catch(function(error) {
                $('.dummyRow > td').html(`No data`);
                let values = Object.values(error.response.data.errors);
                let errors = "<b>Errors</b></br>";
                values.forEach(function(error) {
                    errors += `${error[0]} <b>#</b>`
                })
                $.wnoty({
                    type: 'error',
                    message: errors,
                    autohideDelay: 20000
                });
            })
    })


    // ADD NEW SECONDARY SCHOOL RECORD ASYNC
    $('#secForm').submit(function(e) {
        e.preventDefault();
        $('.dummyRow > td').html(`
            <div class="progress">
                <div class="indeterminate"></div>
            </div>
        `);
        let formData = $(this).serialize();
        let url = `${base_url}applicant/secondary-school/store`;
        console.log(formData);
        console.log(url);
        axios.post(url, formData)
            .then(function(response) {
                $('.dummyRow').fadeOut();
                // console.log(response.data);
                $('#secForm')[0].reset();
                response.data.count > 0 ? $('.secondaryProceed').removeAttr('disabled') : '';
                $(`
                    <tr>
                        <td>${response.data.data.nameOfSchool.charAt(0).toUpperCase() + response.data.data.nameOfSchool.slice(1)}, ${response.data.data.location.charAt(0).toUpperCase() + response.data.data.location.slice(1)} state</td>

                        <td>${response.data.data.certType.toUpperCase()}</td>
                        <td>${response.data.data.secStartYear}</td>
                        <td>${response.data.data.secEndYear}</td>
                        <td>
                            <a href="#" onclick="deleteSecondary(event)" id="deleteRow" data-row_id="${response.data.data.id}" class="red-text">x</a>
                        </td>
                    </tr>
                `).appendTo('.secRows');
                $.wnoty({
                    type: 'success',
                    message: response.data.success,
                    autohideDelay: 5000
                });
            })
            .catch(function(error) {
                $('.dummyRow > td').html(`No data`);
                let values = Object.values(error.response.data.errors);
                let errors = "<b>Errors</b></br>";
                values.forEach(function(error) {
                    errors += `${error[0]} <b>#</b>`
                })
                $.wnoty({
                    type: 'error',
                    message: errors,
                    autohideDelay: 20000
                });
            })
    })


    // ADD NEW TERTIARY EDUCATION RECORD ASYNC
    $('#teriaryForm').submit(function(e) {
        e.preventDefault();
        $('.dummyRow > td').html(`
            <div class="progress">
                <div class="indeterminate"></div>
            </div>
        `);
        let formData = $(this).serialize();
        let url = `${base_url}applicant/tertiary-institution/store`;
        console.log(formData);
        console.log(url);
        axios.post(url, formData)
            .then(function(response) {
                $('.dummyRow').fadeOut();
                // console.log(response.data);
                $('#teriaryForm')[0].reset();
                response.data.count > 0 ? $('.tertiaryProceed').removeAttr('disabled') : '';
                $(`
                    <tr>
                        <td>${response.data.data.qualification.toUpperCase()}</td>
                        <td>${response.data.data.institution.charAt(0).toUpperCase() + response.data.data.institution.slice(1)}</td>

                        <td>${response.data.data.location.charAt(0).toUpperCase() + response.data.data.location.slice(1)}</td>

                        <td>${response.data.data.course.charAt(0).toUpperCase() + response.data.data.course.slice(1)}</td>

                        <td>${response.data.data.grade.charAt(0).toUpperCase() + response.data.data.grade.slice(1)}</td>

                        <td>${response.data.data.TstartYear}</td>
                        <td>${response.data.data.TendYear}</td>
                        <td>
                            <a href="#" onclick="deleteTertiary(event)" id="deleteRow" data-row_id="${response.data.data.id}" class="red-text">x</a>
                        </td>
                    </tr>
                `).appendTo('.tertiaryRows');
                $.wnoty({
                    type: 'success',
                    message: response.data.success,
                    autohideDelay: 5000
                });
            })
            .catch(function(error) {
                $('.dummyRow > td').html(`No data`);
                let values = Object.values(error.response.data.errors);
                let errors = "<b>Errors</b></br>";
                values.forEach(function(error) {
                    errors += `${error[0]} <b>#</b>`
                })
                $.wnoty({
                    type: 'error',
                    message: errors,
                    autohideDelay: 20000
                });
            })
    })


    // ADD NEW NEXT OF KIN RECORD ASYNC
    $('#nextofkinForm').submit(function(e) {
        e.preventDefault();
        $('.dummyRow > td').html(`
            <div class="progress">
                <div class="indeterminate"></div>
            </div>
        `);
        let formData = $(this).serialize();
        let url = `${base_url}applicant/next-of-kin-data/store`;
        console.log(formData);
        console.log(url);
        axios.post(url, formData)
            .then(function(response) {
                $('.dummyRow').fadeOut();
                // console.log(response.data);
                $('#nextofkinForm')[0].reset();
                response.data.count == 2 ? $('.nokProceed').removeAttr('disabled') : '';
                $(`
                    <tr>
                        <td>${ response.data.data.nokfn.charAt(0).toUpperCase() + response.data.data.nokfn.slice(1) }</td>
                        <td>${ response.data.data.nokg.charAt(0).toUpperCase() + response.data.data.nokg.slice(1) }</td>
                        <td>${ response.data.data.nokr.charAt(0).toUpperCase() + response.data.data.nokr.slice(1) }</td>
                        <td>${ response.data.data.noka.charAt(0).toUpperCase() + response.data.data.noka.slice(1) }</td>
                        <td>${ response.data.data.nokp }</td>
                        <td>
                            <a id="deleteRow" onclick="deleteNextofkin(event)" href="#" data-row_id="${ response.data.data.id }" class="red-text">x</a>
                        </td>
                    </tr>
                `).appendTo('.nokTableBody');
                $.wnoty({
                    type: 'success',
                    message: response.data.success,
                    autohideDelay: 5000
                });

            })
            .catch(function(error) {
                $('.dummyRow > td').html(`No data`);
                let values = Object.values(error.response.data.errors);
                let errors = "<b>Errors</b></br>";
                values.forEach(function(error) {
                    errors += `${error[0]} <b>#</b>`
                })
                $.wnoty({
                    type: 'error',
                    message: errors,
                    autohideDelay: 20000
                });
            })
    })


    // ADD NEW CERTIFICATION RECORD ASYNC
    $('#professionalForm').submit(function(e) {
        e.preventDefault();
        $('.dummyRow > td').html(`
            <div class="progress">
                <div class="indeterminate"></div>
            </div>
        `);
        let formData = $(this).serialize();
        let url = `${base_url}applicant/certification/store`;
        console.log(formData);
        console.log(url);
        axios.post(url, formData)
            .then(function(response) {
                $('.dummyRow').fadeOut();
                // console.log(response.data);
                $('#professionalForm')[0].reset();
                $(`
                    <tr>
                        <td>${ response.data.data.institute.charAt(0).toUpperCase() + response.data.data.institute.slice(1) }</td>
                        <td>${ response.data.data.location.charAt(0).toUpperCase() + response.data.data.location.slice(1) }</td>
                        <td>${ response.data.data.title.charAt(0).toUpperCase() + response.data.data.title.slice(1) }</td>
                        <td>${ response.data.data.startdate }</td>
                        <td>${ response.data.data.enddate }</td>
                        <td>
                            <a id="deleteRow" onclick="deleteCertification(event)" href="#" data-row_id="${ response.data.data.id }" class="red-text">x</a>
                        </td>
                    </tr>
                `).appendTo('.professionalRows');
                $.wnoty({
                    type: 'success',
                    message: response.data.success,
                    autohideDelay: 5000
                });
            })
            .catch(function(error) {
                $('.dummyRow > td').html(`No data`);
                let values = Object.values(error.response.data.errors);
                let errors = "<b>Errors</b></br>";
                values.forEach(function(error) {
                    errors += `${error[0]} <b>#</b>`
                })
                $.wnoty({
                    type: 'error',
                    message: errors,
                    autohideDelay: 20000
                });
            })
    })

    // ADD NEW WORK EXPERIENCE RECORD ASYNC
    $('#workExpirienceForm').submit(function(e) {
        e.preventDefault();
        $('.dummyRow > td').html(`
            <div class="progress">
                <div class="indeterminate"></div>
            </div>
        `);
        let formData = $(this).serialize();
        let url = `${base_url}applicant/experience/store`;
        console.log(formData);
        console.log(url);
        axios.post(url, formData)
            .then(function(response) {
                $('.dummyRow').fadeOut();
                // console.log(response.data);
                $('#workExpirienceForm')[0].reset();
                $(`
                    <tr>
                        <td>${ response.data.data.organisation.charAt(0).toUpperCase() + response.data.data.organisation.slice(1) }</td>

                        <td>${ response.data.data.location.charAt(0).toUpperCase() + response.data.data.location.slice(1) }</td>

                        <td>${ response.data.data.role.charAt(0).toUpperCase() + response.data.data.role.slice(1) }</td>
                        
                        <td>${ response.data.data.startDate }</td>
                        <td>${ response.data.data.endDate }</td>
                        <td>
                            <a id="deleteRow" onclick="deleteExperience(event)" href="#" data-row_id="${ response.data.data.id }" class="red-text">x</a>
                        </td>
                    </tr>
                `).appendTo('.experienceRows');
                $.wnoty({
                    type: 'success',
                    message: response.data.success,
                    autohideDelay: 5000
                });
            })
            .catch(function(error) {
                $('.dummyRow > td').html(`No data`);
                let values = Object.values(error.response.data.errors);
                let errors = "<b>Errors</b></br>";
                values.forEach(function(error) {
                    errors += `${error[0]} <b>#</b>`
                })
                $.wnoty({
                    type: 'error',
                    message: errors,
                    autohideDelay: 20000
                });
            })
    })


});

// DELETE PRIMARY SCHOOL RECORD
function deletePrimary(e) {
    let id = e.currentTarget.dataset.row_id;
    let url = `${base_url}applicant/primary-school/delete/`;
    let verify = confirm('Are you sure you want to delete this record?');
    if (verify) {
        axios.delete(`${url}${id}`)
            .then(response => {
                if (response.data.status) {
                    $.wnoty({
                        type: 'success',
                        message: 'Record deleted successfully',
                        autohideDelay: 5000
                    });
                    console.log($('a[data-row_id=' + response.data.id + ']').parent().parent().hide());
                }
            });
    }
}

// DELETE SECONDARY SCHOOL RECORD
function deleteSecondary(e) {
    let id = e.currentTarget.dataset.row_id;
    let url = `${base_url}applicant/secondary-school/delete/`;
    let verify = confirm('Are you sure you want to delete this record?');
    if (verify) {
        axios.delete(`${url}${id}`)
            .then(response => {
                if (response.data.status) {
                    $.wnoty({
                        type: 'success',
                        message: 'Record deleted successfully',
                        autohideDelay: 5000
                    });
                    console.log($('a[data-row_id=' + response.data.id + ']').parent().parent().hide());
                }
            });
    }
}

// DELETE TERTIARY SCHOOL RECORD
function deleteTertiary(e) {
    let id = e.currentTarget.dataset.row_id;
    let url = `${base_url}applicant/tertiary-institution/delete/`;
    let verify = confirm('Are you sure you want to delete this record?');
    if (verify) {
        axios.delete(`${url}${id}`)
            .then(response => {
                if (response.data.status) {
                    $.wnoty({
                        type: 'success',
                        message: 'Record deleted successfully',
                        autohideDelay: 5000
                    });
                    console.log($('a[data-row_id=' + response.data.id + ']').parent().parent().hide());
                }
            });
    }
}

// DELETE NEXT OF KIN RECORD
function deleteNextofkin(e) {
    let id = e.currentTarget.dataset.row_id;
    let url = `${base_url}applicant/next-of-kin-data/delete/`;
    let verify = confirm('Are you sure you want to delete this record?');
    if (verify) {
        axios.delete(`${url}${id}`)
            .then(response => {
                if (response.data.status) {
                    $.wnoty({
                        type: 'success',
                        message: 'Record deleted successfully',
                        autohideDelay: 5000
                    });
                    console.log($('a[data-row_id=' + response.data.id + ']').parent().parent().hide());
                }
            });
    }
}


// DELETE CERTIFICATION RECORD
function deleteCertification(e) {
    let id = e.currentTarget.dataset.row_id;
    let url = `${base_url}applicant/certification/delete/`;
    let verify = confirm('Are you sure you want to delete this record?');
    if (verify) {
        axios.delete(`${url}${id}`)
            .then(response => {
                if (response.data.status) {
                    $.wnoty({
                        type: 'success',
                        message: 'Record deleted successfully',
                        autohideDelay: 5000
                    });
                    console.log($('a[data-row_id=' + response.data.id + ']').parent().parent().hide());
                }
            });
    }
}


// DELETE EXPERIENCE RECORD
function deleteExperience(e) {
    let id = e.currentTarget.dataset.row_id;
    let url = `${base_url}applicant/experience/delete/`;
    let verify = confirm('Are you sure you want to delete this record?');
    if (verify) {
        axios.delete(`${url}${id}`)
            .then(response => {
                if (response.data.status) {
                    $.wnoty({
                        type: 'success',
                        message: 'Record deleted successfully',
                        autohideDelay: 5000
                    });
                    console.log($('a[data-row_id=' + response.data.id + ']').parent().parent().hide());
                }
            });
    }
}


////////////////////////////////// ADMINISTRATIVE CONTROLS GOES HERE //////////////////////////////////


$(document).on('change', '.selectAll', function() {
    if (this.checked) {
        $('.applicantCheckbox').attr('checked', true);
    } else {
        $('.applicantCheckbox').attr('checked', false);
    }
});


$(document).on('click', '#shortlistBtn', function() {
    let id = [];
    if (confirm('Are you sure you want to shortlist the selected candidate(s)?')) {
        $('.applicantCheckbox:checked').each(function() {
            id.push($(this).val())
        });
        if (id.length > 0) {
            // console.log(id);
            axios.post(`/administrator/applicants/shortListApplicants`, { applicants: id })
                .then(function(response) {
                    // console.log(response);
                    if (response.data.status) {
                        $.wnoty({
                            type: 'success',
                            message: response.data.message,
                            autohideDelay: 5000
                        });
                        $('#users-table').DataTable().ajax.reload();
                    }
                });
        } else {
            alert('You must select at least one candidate!');
        }
    }
});