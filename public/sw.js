self.addEventListener('push', function(event) {
    if (event.data) {
        const pushedData = event.data.json();
        event.waitUntil(
            self.registration.showNotification(pushedData.title, {
                body: pushedData.body,
            })
        );
    }
});