jQuery(function($){
     $(document).on('click', '#ht-scan-duplicates', function() {
        $('#ht-duplicate-log').html('Scanning for duplicates...');
        
        $.ajax({    
            url: ajaxurl,
            type: 'POST',
            data: { action: 'ht_scan_duplicate_media' },
            success: function(res) {
                if(res.success) {
                    $('#ht-duplicate-log').html(JSON.stringify(res, null, 2));
                } else {
                    $('#ht-duplicate-log').html('Error scanning for duplicates.');
                }
            },
            error: function() {
                $('#ht-duplicate-log').html('AJAX error while scanning.');
            }
        });
     });
        $(document).on('click', '#ht-delete-duplicates', function() {
        $('#ht-duplicate-log').html('Scanning for duplicates...');
        $.ajax({    
            url: ajaxurl,
            type: 'POST',
            data: { action: 'ht_delete_duplicate_media' },
            success: function(res) {
                if(res.success) {
                    $('#ht-duplicate-log').html(JSON.stringify(res, null, 2));
                } else {
                    $('#ht-duplicate-log').html('Error deleting duplicates.');
                }
            },
            error: function() {
                $('#ht-duplicate-log').html('AJAX error while deleting duplicates.');
            }
        });
     });
     function runBatch(offset = 0) {

    jQuery.post(
        ajaxurl,
        {
            action: 'generate_collection_og_images',
            offset: offset
        },
        function(r) {

            console.log(r);

            if (!r.data.finished) {
                runBatch(r.data.next);
            } else {
                console.log('Done');
            }
        }
    );
}
     $(document).on('click', '#ht-create-collection-og-images', function(e) {
        e.preventDefault();
        // Handle the click event for creating collection OG images
        runBatch();

     });
     $(document).on('click', '#btnFetch', function() {
         let type = $('#import_type').val();
         let time = $('#import_endpoint').val();

    if (!type || !time) {
        alert('Select both fields');
        return;
    }
        resetUI();
         table.ajax.reload();
     })
    let table = $('#tableTrending').DataTable({
        processing: true,
        serverSide: true,
        paging: true,
        searching: false,
        ordering: false,
        deferLoading: 0,
        lengthChange: false,
        pageLength: 20, 
        ajax: function (data, callback) {

            let type = $('#import_type').val();
            let time = $('#import_endpoint').val();

            if (!type || !time) return;

            let page = (data.start / data.length) + 1;

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'ht_movie_fetch_trending',
                    type: type,
                    time: time,
                    page: page
                },
                success: function (response) {

                    if (response.success) {
                        callback({
                            data: response.data.results,
                            recordsTotal: response.data.total_results,
                            recordsFiltered: response.data.total_results
                        });
                    }
                }
            });
        },

        columns: [
            {
        data: null,
        orderable: false,
        render: function (data) {
             if (data.imported) {
            return `<span class="badge bg-success">Imported</span>`;
        }
         let disabled = isImporting ? 'disabled' : '';
         console.log(isImporting);
         return `<input data-id="${data.id}"
                       type="checkbox" 
                       class="row-check" 
                       value="${data.id}"
                       ${disabled}>`;
        }
    },
            { data: 'id' },
            {
                data: 'poster',
                render: function (data) {
                    return `<img src="${data}" width="50"/>`;
                }
            },
            { data: 'title' },
            { data: 'overview' },
            { data: 'release_date' }
        ]
    });
     let pageSync = 1;
    let type = 'show'; // or switch UI

    $('#sync_tmdb_btn').on('click', function () {

        function runBatch() {

            $.post(ajaxurl, {
                action: 'sync_tmdb_ids',
                type: type,
                page: pageSync
            }, function (res) {

                if (res.success) {

                    $('#sync_log').append(
                        `<p>Page ${pageSync}: ${res.data.found} updated</p>`
                    );

                    if (res.data.has_more) {
                        pageSync++;
                        runBatch(); // next batch
                    } else {
                        $('#sync_log').append('<p>✅ Done</p>');
                    }
                }
            });

        }

        runBatch();
    });

    jQuery(document).ready(function ($) {
    function loadUnmatched() {
        $.post(ajaxurl, { action: 'get_unmatched_posts' }, function (res) {

            let rows = '';

            res.data.forEach(item => {
                rows += `
                <tr>
                    <td>${item.title}</td>
                    <td>${item.year}</td>
                    <td>${item.type}</td>
                    <td>
                        <button class="button searchBtn" 
                            data-id="${item.id}" 
                            data-title="${item.title}" 
                            data-type="${item.type}">
                            Search
                        </button>
                    </td>
                </tr>`;
            });

            $('#retryTable tbody').html(rows);
        });
    }

    loadUnmatched();

    // 🔍 Search click
    $(document).on('click', '.searchBtn', function () {

        let postId = $(this).data('id');
        let title  = $(this).data('title');
        let type   = $(this).data('type');

        $.post(ajaxurl, {
            action: 'search_tmdb_manual',
            query: title,
            type: type
        }, function (res) {

            let html = '<div class="tmdb-results">';

            res.data.forEach(item => {
                let name = item.title || item.name;
                let date = item.release_date || item.first_air_date;

                html += `
                    <div>
                        <strong>${name}</strong> (${date})
                        <button class="selectTmdb" 
                            data-post="${postId}" 
                            data-id="${item.id}">
                            Select
                        </button>
                    </div>
                `;
            });

            html += '</div>';
            $('#tmdb-modal-content').html(html);


            tb_show('Select TMDB Match', '#TB_inline?width=600&height=550&inlineId=tmdb-modal-content');
        });

    });

    // ✅ Save selection
    $(document).on('click', '.selectTmdb', function () {

        let postId = $(this).data('post');
        let tmdbId = $(this).data('id');

        $.post(ajaxurl, {
            action: 'save_tmdb_id',
            post_id: postId,
            tmdb_id: tmdbId
        }, function () {
            alert('Saved!');
            location.reload();
        });

    });

});

$('#select_all').on('click', function () {
    $('.row-check').prop('checked', this.checked);
    let anyChecked = $('.row-check:checked').length > 0;
    
    $('#btnImportSelected').prop('disabled', !anyChecked);
});
$('#tableTrending').on('change', '.row-check', function () {
    if (!this.checked) {
        $('#select_all').prop('checked', false);
    }
     let anyChecked = $('.row-check:checked').length > 0;
    
         $('#btnImportSelected').prop('disabled', !anyChecked);

});
let importQueue = [];
let total = 0;
let done = 0;
let failed = [];
let isImporting = false;
let importedIds = new Set();
function toggleControls(disable) {
    $('#btnFetch').prop('disabled', disable);
    $('#import_type').prop('disabled', disable);
    $('#import_endpoint').prop('disabled', disable);
}
$('#btnImportSelected').on('click', function () {
      importQueue = [];
        $('.row-check:checked').each(function () {
                importQueue.push($(this).val());
        })

     if (importQueue.length === 0) {
        alert('No items selected');
        return;
    }
      total = importQueue.length;
       done = 0;
      failed = [];
      isImporting=true;
      startImportUI();

     processQueue();
});
function startImportUI(){
    toggleControls(true);
    $('#btnImportSelected').prop('disabled', true);

    // disable pagination (UI only)
    $('button.page-link').css({
        'pointer-events': 'none',
        'opacity': '0.5'
    });

    // re-render checkboxes disabled (NO pagination reset)
    table.rows().invalidate();

}
function markRowImported(id) {

    let row = table.row(function (idx, data) {
        return data.id == id;
    });

    if (row) {
        let rowData = row.data();
        rowData.imported = true;

        row.data(rowData).invalidate(); // ✅ NO pagination reset
    }
}
function finishImportUI() {

    isImporting = false;

    // enable controls
    toggleControls(false);

    // keep import button disabled (as per requirement)
    $('#btnImportSelected').prop('disabled', true);

    // enable pagination
    $('button.page-link').css({
        'pointer-events': 'auto',
        'opacity': '1'
    });

    // refresh table UI
    table.rows().invalidate();
    $('#select_all').prop('checked',false);


}
function processQueue() {
   if (!importQueue.length) {
        isImporting = false;
        $('#log').append('<p>✅ Done. Failed: ' + failed.length + '</p>');
        $('#btnImportSelected').prop('disabled', true);
        toggleControls(false);
        finishImportUI();
        return;
    }
        let item = importQueue.shift();
        
        let type=$("#import_type").val();
         $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'ht_bulk_import_tmdb',
            id: item,
            type:type
        },
        success: function (res) {
            done++;
             if (!res.success) {
                  failed.push(item);
                  $('#log').append('<p style="color:red;">❌ Failed: ' + item + '</p>');
             }else{
                markRowImported(item);

                $('#log').append('<p>✅ Imported: ' + item + '</p>');
             }
            updateProgress();
            processQueue();
        },
        error: function () {
             done++;
             failed.push(item);
            $('#log').append('<p style="color:red;">❌ Error: ' + item + '</p>');

            updateProgress();
            processQueue();
        }
    });

}
function updateProgress() {
    let percent = Math.round((done / total) * 100);
    $('#progressBar').css('width', percent + '%');
}
table.on('draw.dt', function () {

    
    if (isImporting) return;

    resetUI();
});
function resetUI() {

    // clear log
    $('#log').html('');

    // reset progress bar
    $('#progressBar').css('width', '0%');

    // reset counters (optional but clean)
    total = 0;
    done = 0;
    failed = [];
}

let kwQueue = [];
let kwTotal = 0;
let kwDone = 0;

jQuery(document).on('click', '#btnFixKeywords', function () {

    jQuery('#kwLog').html('');
    jQuery('#kwProgressBar').css('width', '0%');

    jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'ht_get_posts_missing_keywords'
        },
        success: function (res) {

            kwQueue = res.data;
            kwTotal = kwQueue.length;
            kwDone = 0;

            if (!kwTotal) {
                logKW('Nothing to fix');
                return;
            }

            processKWQueue();
        }
    });
});
function processKWQueue() {

    if (!kwQueue.length) {
        logKW('✅ Done fixing keywords');
        return;
    }

    let item = kwQueue.shift();

    jQuery.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'ht_fix_keywords',
            post_id: item.post_id,
            tmdb_id: item.tmdb_id,
            type: item.type
        },
        success: function (res) {

            kwDone++;

            if (res.success) {
                logKW(`✔ Updated: ${item.post_id}`);
            } else {
                logKW(`❌ Failed: ${item.post_id}`, true);
            }

            updateKWProgress();
            processKWQueue();
        }
    });
}
function updateKWProgress() {

    let percent = Math.round((kwDone / kwTotal) * 100);
    jQuery('#kwProgressBar').css('width', percent + '%');
}

function logKW(msg, error = false) {

    let color = error ? 'red' : 'black';
    jQuery('#kwLog').append(`<p style="color:${color}">${msg}</p>`);
}

$(document).on('click', '#btnTestingWatch', function () {
    $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            action: 'ht_testing_watch'
        },
        success: function (res) {
            alert('Check console for response');
            console.log(res);
        }
    });           
})
        $('#ht-sync-tmdb').on('click', function(){

            let btn = $(this);
            let status = $('#ht-sync-status');

            btn.prop('disabled', true).text('Syncing...');

            $.post(ajaxurl, {
                action: 'ht_tmdb_manual_sync',
                post_id: btn.data('id')
            }, function(res){

                if(res.success){
                    status.html('✅ Synced');
                } else {
                    status.html('❌ Failed');
                }

                btn.prop('disabled', false).text('🔄 Sync from TMDB');

            });
        });

        $('#syncAttachments').on('click', function () {

    let page = 1;
    let totalProcessed = 0;

    function run() {

        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'ht_sync_attachments',
                page: page
            },
            success: function (res) {

                if (!res.success) return;

                totalProcessed += res.data.updated;

                $('#syncLog').append(
                    `<div>Page ${page}: ${res.data.updated} images fixed</div>`
                );

                // progress bar (fake %)
                let percent = Math.min(page * 10, 100);
                $('#syncBar').css('width', percent + '%');

                if (res.data.has_more) {
                    page++;
                    run();
                } else {
                    $('#syncLog').append(
                        `<div><b>Done! Total fixed: ${totalProcessed}</b></div>`
                    );
                    $('#syncBar').css('width', '100%');
                }
            }
        });
    }

    run();
});

    $('#fixCastID').on('click', function () {

        let page = 1;
        let totalFixed = 0;
        let totalChecked = 0;
        let running = true;

        $('#castLog').html('');
        $('#castProgress').css('width', '0%');

        function runBatch() {

            if (!running) return;

            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'ht_fix_cast_person_id',
                    page: page
                },
                success: function (res) {

                    if (!res.success) {
                        $('#castLog').append(`<div style="color:red;">Error on page ${page}</div>`);
                        return;
                    }

                    let data = res.data;

                    totalFixed += data.fixed;
                    totalChecked += data.checked;

                    $('#castLog').append(
                        `<div>Page ${page} → Fixed: ${data.fixed}, Checked: ${data.checked}</div>`
                    );

                    // progress bar (fake but smooth)
                    let percent = Math.min(page * 5, 100);
                    $('#castProgress').css('width', percent + '%');

                    if (data.has_more) {
                        page++;
                        setTimeout(runBatch, 800); // 🔥 delay prevents API spam
                    } else {
                        $('#castProgress').css('width', '100%');

                        $('#castLog').append(
                            `<div style="margin-top:10px;">
                                <b>Done ✅</b><br>
                                Total Fixed: ${totalFixed}<br>
                                Total Checked: ${totalChecked}
                            </div>`
                        );

                        running = false;
                    }
                },
                error: function () {
                    $('#castLog').append(`<div style="color:red;">AJAX Error</div>`);
                }
            });
        }

        runBatch();
    });

    $('#syncCastFull').on('click', function () {

        let pageCast = 1;
        let totalCast = 0;
        let running = true;

        $('#castFullLog').html('');
        $('#castFullBar').css('width', '0%');

        function runCast() {

            if (!running) return;

            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'ht_sync_cast_full',
                    page: pageCast
                },
                success: function (res) {

                    if (!res.success) {
                        $('#castFullLog').append(`<div style="color:red;">Error</div>`);
                        return;
                    }

                    let updated = res.data.updated;

                    totalCast += updated;

                    $('#castFullLog').append(
                        `<div>Page ${pageCast} → Updated: ${updated}</div>`
                    );

                    let percent = Math.min(pageCast * 3, 100);
                    $('#castFullBar').css('width', percent + '%');

                    if (res.data.done===true) {
                        running = false;
                        $('#castFullBar').css('width', '100%');

                        $('#castFullLog').append(
                            `<div style="margin-top:10px;">
                                <b>Done ✅</b><br>
                                Total Updated: ${totalCast}
                            </div>`
                        );
                        return;
                    }

                    pageCast++;

                    // 🔥 delay to avoid TMDB rate limit
                    setTimeout(runCast, 800);
                },
                error: function () {
                    $('#castFullLog').append(`<div style="color:red;">AJAX error</div>`);
                }
            });
        }

        runCast();
    });
    $('#testProviderList').on('click', function () {
        $.ajax({
            url: ajaxurl,
            method: 'POST',
            data: {
                action: 'ht_test_provider_list'
            },
            success: function (res) {
                alert('Check console for response');
                console.log(res);
            },
            error: function () {
                alert('AJAX error');
            }
        });

    })

    $('#syncProviders').on('click', function () {

        let page = 1;
        let total = 0;
        let running = true;

        $('#providerLog').html('');
        $('#providerBar').css('width', '0%');

        function run() {

            if (!running) return;

            $.ajax({
                url: ajaxurl,
                method: 'POST',
                data: {
                    action: 'ht_sync_watch_providers',
                    page: page
                },
                success: function (res) {

                    if (!res.success) {
                        $('#providerLog').append('<div style="color:red;">Error</div>');
                        return;
                    }

                    let updated = res.data.updated;
                    total += updated;

                    $('#providerLog').append(
                        `<div>Page ${page} → Updated: ${updated}</div>`
                    );

                    let percent = Math.min(page * 5, 100);
                    $('#providerBar').css('width', percent + '%');

                    if (res.data.has_more) {
                        page++;
                        setTimeout(run, 700); // 🔥 avoid API limit
                    } else {
                        running = false;

                        $('#providerBar').css('width', '100%');

                        $('#providerLog').append(
                            `<div><b>Done ✅ Total Updated: ${total}</b></div>`
                        );
                    }
                },
                error: function () {
                    $('#providerLog').append('<div style="color:red;">AJAX Error</div>');
                }
            });
        }

        run();
    });
})