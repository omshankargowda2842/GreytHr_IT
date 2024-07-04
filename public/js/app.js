/**
 * First we will load all of this project's JavaScript dependencies which
 * includes React and other helpers. It's a great starting point while
 * building robust, powerful web applications using React + Laravel.
 */

import './bootstrap';

/**
 * Next, we will create a fresh React component instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
function toggleDetails(sectionId, clickedLink) {
    const tabs = ['personalDetails', 'accountDetails', 'familyDetails', 'employeeJobDetails', 'assetsDetails'];

    const links = document.querySelectorAll('.custom-nav-link-pi');
    links.forEach(link => link.classList.remove('active'));

    clickedLink.classList.add('active');

    tabs.forEach(tab => {
        const tabElement = document.getElementById(tab);
        if (tab === sectionId) {
            tabElement.style.display = 'block';
        } else {
            tabElement.style.display = 'none';
        }
    });
}

document.getElementById('personalDetails').style.display = 'block';


  // Function to change the quote text
  function changeQuote() {
    const quotes = [{
            text: "Life is 10% what happens to us and 90% how we react to it.",
            author: "Dennis P. Kimbro"
        },
        {
            text: "Your new Employee Self Service portal is here. Watch the video to learn more.",
            author: "Anonymous"
        },
        {
            text: "Things usually work out in the end. What if they don't? That just means you haven't come to the end yet.",
            author: "Jeanette Walls"
        }
        // Add more quotes here as needed
    ];

    const quoteElement = document.querySelector('.quote-text');
    const authorElement = document.querySelector('.author-text');
    const randomIndex = Math.floor(Math.random() * quotes.length);
    const randomQuote = quotes[randomIndex];

    quoteElement.textContent = randomQuote.text;
    authorElement.textContent = `- ${randomQuote.author}`;
}

// Call the function to initially set the quote
changeQuote();

// Set an interval to change the quote every 5 seconds (5000 milliseconds)
setInterval(changeQuote, 5000);


var combinedData = {
datasets: [
    {
        data: [
            {{ !empty($salaries) ? $salaries->calculateTotalAllowance() : 0 }},
            2, // Placeholder value for the second dataset
        ],
        backgroundColor: [
            '#000000', // Color for Gross Pay
        ],
    },
    
    {
        data: [
            {{ !empty($salaries) && method_exists($salaries, 'calculateTotalDeductions') ? $salaries->calculateTotalDeductions() : 0 }},
            {{ !empty($salaries) && method_exists($salaries, 'calculateTotalAllowance') ? $salaries->calculateTotalAllowance() - $salaries->calculateTotalDeductions() : 0 }},
        ],
        backgroundColor: [
            '#B9E3C6', // Color for Deductions
            '#1C9372', // Color for Net Pay
        ],
    },
],
};

var outerCtx = document.getElementById('combinedPieChart').getContext('2d');

var combinedPieChart = new Chart(outerCtx, {
type: 'doughnut',
data: combinedData,
options: {
    cutout: '60%', // Adjust the cutout to control the size of the outer circle
    legend: {
        display: false,
    },
    tooltips: {
        enabled: false,
    },
},
}
);

