function flashMessage(message) {
    document.body.insertAdjacentHTML('beforeend', `
    <div class="flash-m fixed top-1 left-1/2 transform -translate-x-1/2 rounded bg-blue-500 text-white py-2 elementToFadeInAndOut">
        <p class="mx-4">
            ${message}
        </p>
    </div>
    `);
    setTimeout(() => {
        document.getElementsByClassName('flash-m').item(0).remove()
    }, 3000);
}

window.flashMessage = flashMessage