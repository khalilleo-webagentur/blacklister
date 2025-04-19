$(document).ready(function () {

    setTimeout(function () {
        $(".alert").alert('close').fadeOut();
    }, 6000);

    let selector = $('.selector');

    if (selector.length) {
        selector.on('click', function (e) {
            e.preventDefault();
            $('.ID').val($(this).attr('data-id'));
        });
    }

    // copy to clipboard
    let copyToClipBoard = $('.copyToClipBoard');
    if (copyToClipBoard.length) {
        copyToClipBoard.on('click', function (e) {
            e.stopPropagation();
            let content = $(this).attr('data-token');
            if (content) {
                copyContent(content);
                alert('( ' + content + ') has been copied to clipboard.');
            } else {
                alert('cannot be copied to clipboard.');
            }
        });
    }
});

function isLocalStorageAvailable() { return typeof (Storage) !== "undefined" }

async function copyContent(text) {
    try {
        if (isLocalStorageAvailable) {
            await navigator.clipboard.writeText(text);
        }
    } catch (err) {
        console.error('clipboard is not available on your Browser.');
    }
}