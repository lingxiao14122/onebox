
document.getElementById('bell').addEventListener('click', clickBellHandler)

function clickBellHandler() {
    const button = this
    const icon = button.firstElementChild
    const notificationBox = button.nextElementSibling

    icon.classList.toggle('bell-shaking')
    if (notificationBox.classList.toggle('notification-box-in')) {
        notificationBox.classList.remove('notification-box-out')
    } else {
        notificationBox.classList.remove('notification-box-in')
        notificationBox.classList.toggle('notification-box-out')
    }
    
}


document.getElementById('bell').nextElementSibling