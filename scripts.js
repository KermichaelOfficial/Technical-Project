document.addEventListener('DOMContentLoaded', (event) => {
    const tweetForm = document.getElementById('tweet-form');
    const tweetText = document.getElementById('tweet-text');
    const tweetsContainer = document.getElementById('tweets');

    tweetForm.addEventListener('submit', function (e) {
        e.preventDefault();
        const tweetContent = tweetText.value.trim();

        if (tweetContent !== "") {
            const tweetDiv = document.createElement('div');
            tweetDiv.classList.add('tweet');
            tweetDiv.innerHTML = `<h3><?php echo htmlspecialchars($username); ?></h3><p>${tweetContent}</p>`;
            tweetsContainer.insertBefore(tweetDiv, tweetsContainer.firstChild);
            tweetText.value = "";
        }
    });
});
document.addEventListener('DOMContentLoaded', () => {
    // Initialize dark mode based on localStorage value
    if (localStorage.getItem('dark-mode') === 'enabled') {
        document.body.classList.add('dark-mode');
    }

    // Toggle dark mode
    window.toggleDarkMode = function() {
        document.body.classList.toggle('dark-mode');
        
        // Save the state to localStorage
        if (document.body.classList.contains('dark-mode')) {
            localStorage.setItem('dark-mode', 'enabled');
        } else {
            localStorage.setItem('dark-mode', 'disabled');
        }
    };
});
    document.addEventListener('DOMContentLoaded', () => {
        // Dark mode initialization
        if (localStorage.getItem('dark-mode') === 'enabled') {
            document.body.classList.add('dark-mode');
        }

        // Toggle dark mode
        window.toggleDarkMode = function() {
            document.body.classList.toggle('dark-mode');
            // Save the current state to localStorage
            if (document.body.classList.contains('dark-mode')) {
                localStorage.setItem('dark-mode', 'enabled');
            } else {
                localStorage.removeItem('dark-mode');
            }
        };
    });
    
