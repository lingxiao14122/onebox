document.addEventListener('DOMContentLoaded', () => {
    initEchoChannel()
})

function initEchoChannel() {
    Echoo.private('App.Models.User.' + userId)
        .notification(async (data) => {
            console.log(data);
            const notifications = await axios.get('notifications')
            const html = renderJSONToHTML(notifications.data)
            const content = document.getElementById('notification-content')
            content.innerHTML = html
            const bellParent = document.getElementById('bell-parent')
            bellParent.dispatchEvent(new Event('ring'))
        });
}

function renderJSONToHTML(notifications) {
    const mediumTime = new Intl.DateTimeFormat("en", {
        timeStyle: "short",
    });
    const mediumDate = new Intl.DateTimeFormat("en", {
        year: "2-digit",
        month: "2-digit",
        day: "2-digit"
    });
    let html = ""
    notifications.forEach(no => {
        const date = Date.parse(no.created_at)
        const datestr = `${mediumDate.format(date)} ${mediumTime.format(date)}`
        html += `
        <div class="my-2 notification-item">
            <p class="mb-1 text-sm font-semibold text-slate-700">
                ${no.data.name} reach minimum, <br>${no.data.stock_count} quantity left
            </p>
            <p class="text-sm font-semibold text-slate-500">${datestr}</p>
        </div>
        `
    });
    return html
}