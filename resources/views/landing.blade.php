<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>ETEEAP Landing</title>
    <link rel="icon" type="image/png" href="{{ asset('images/eteeap_logo.png') }}">
<link href="https://fonts.googleapis.com/css2?family=Raleway:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar">
  <div class="logo">
    <img src="{{ asset('images/eteeap_logo.png') }}" alt="logo">
    <h2>BU-ETEEAP</h2>
  </div>

  <div class="hamburger" id="hamburger">
    <span></span>
    <span></span>
    <span></span>
  </div>

  <div class="nav-links" id="nav-links">
    <a href="#home" class="active">Home</a>
    <a href="#about">About Us</a>
    <a href="#news">News</a>
    <a href="#apply">How to Apply?</a>
    <a href="#faq">FAQs</a>
    <a href="#contact">Contact Us</a>
    <a href="{{ route('login') }}" class="login-btn">LOGIN</a>
  </div>
</nav>

<!-- HERO SECTION -->
<section id="home" class="hero">
  <div class="hero-content">
    <h1>Earn your Degree through <br><span>ETEEAP</span></h1>
    <a href="{{ route('login', ['mode' => 'signup']) }}" class="apply-btn">Apply Now!</a>
  </div>
</section>

<!-- ABOUT SECTION -->
<section id="about" class="about">
  <div class="about-text">
    <h2>About Us</h2>
    <p>
      Bicol University, under the leadership of former President Emiliano A. Aberin, committed to participating in the initial implementation of the Expanded Tertiary Education Equivalency and Accreditation Program (ETEEAP) in 1999. Today, 
      BU remains to be one of the 98 deputized higher education institutions (HEIs) in the Philippines authorized to implement this program.
      <br><br>
      ETEEAP reflects the government’s mission to provide quality education and alternative pathways for learners from diverse backgrounds.
    </p>

    <button id="readMoreBtn">Read More</button>

    <div id="moreContent">

    <p><strong>ETEEAP OBJECTIVES</strong></p>

    <p><strong>General Objective:</strong></p>
    <p>
      To develop fully the system of equivalency and accreditation in higher education as a bridging mechanism for the flexible entry and exchange among the formal, non-formal and informal systems.
    </p>

      <p><strong>Specific Objectives:</strong></p>

      <ol>
        <li>Establish a mechanism for assessment and accreditation of prior formal, non-formal and informal learning of individuals toward the granting of certificates and awards in higher education;</li>
        <li>Establish guidelines for the award of undergraduate academic degrees to deserving individuals;</li>
        <li>Incorporate the developments of K-12, the new General Education curriculum (CMO No. 20, s. 2013), the Philippine commitments to international/multilateral agreements in education and related acts;</li>
        <li>Enrich the academic faculty of HEIs by creating the conditions that will encourage industry experts to share their expertise in the academe;</li>
        <li>Establish standards and guidelines for education, accreditation and equivalency in the undergraduate level to ensure harmony in its implementation;</li>
        <li>Strengthen the system of credit transfer from the formal, informal and non-formal education system;</li>
        <li>Expand the coverage of ETEEAP implementation to include ICCs/IPs, OFWs, PWDs, and IDPs;</li>
        <li>Entitle ETEEAP student applicants to scholarships and/or financial assistance given by the Commission.</li>
      </ol>
    </div>
  </div>

  <div class="about-slider">
  <div class="content-wrapper">
    <img src="{{ asset('images/balilo.jpeg') }}" alt="logo">
    <h3>Dr. Benedicto B. Balilo Jr.</h3>
    <p>Dean, Open University</p>
  </div>
</div>
</div>
</section>

<!-- NEWS SECTION -->
<section id="news" class="news">
  <h2 class="news-title">News</h2>

  <div class="news-container" id="fb-news-container">
    <p style="text-align: center; width: 100%;">Loading latest news...</p>
  </div>
</section>


<!-- HOW TO APPLY -->
<section id="apply" class="apply">
  <div class="apply-header">
    <h2>How to Apply?</h2>
    <p>Please follow the steps below to complete your application process</p>
  </div>

  <div class="steps">

    <!-- ONSITE -->
    <div class="step">
      <h3>Onsite Submission</h3>

      <p class="location">
        3rd Floor, University Library Building, Bicol University Main Campus, Daraga, Albay
      </p>

      <p>
        Please submit your documents with a table of contents and proper tabbing in a long folder.
        Each document should be arranged in alphabetical order.
      </p>

      <div class="color-coding">
        <strong>Color Coding by Program:</strong>
        <ul>
          <li><span class="blue">Blue</span> – BS Computer Science</li>
          <li><span class="cream">Cream</span> – BS Nursing</li>
          <li><span class="pink">Pink</span> – BS Automotive Technology</li>
          <li><span class="violet">Violet</span> – BS Fisheries</li>
          <li><span class="orange">Orange</span> – AB Communication</li>
        </ul>
      </div>

      <!-- ONSITE EXAMPLE -->
<div class="example">
  <div class="example-header">
    <button type="button" class="btn-toggle-example" onclick="toggleExample()">
      See Example Here
    </button>
  </div>

  <div id="exampleImages" class="example-images" style="display: none;">
    <img src="{{ asset('images/toc.png') }}" alt="Table of Contents Example">
    <img src="{{ asset('images/folder.png') }}" alt="Tabbed Folder Example">
  </div>
</div>
    </div>

    <!-- ONLINE -->
    <div class="step">
  <div class="step-content-wrapper"> <div class="text-side"> <h3>Online Submission</h3>
      <p>You may submit your documents through the QR Code or using the link below:</p>
      <p class="link">
        <a href="https://bit.ly/BUETEEAPApplication" target="_blank">
          https://bit.ly/BUETEEAPApplication
        </a>
      </p>
      <p>Documents must be submitted in PDF format.</p>
      <p class="note">
        Please note that we only accept online submissions through Google Forms and onsite submissions.
      </p>
    </div>

    <div class="qr">
      <img src="{{ asset('images/qr.png') }}" alt="QR Code">
    </div>

  </div> </div>

  
  <section class="programs-section">
    <div class="programs-title-container">
        <h2 class="programs-title">PROGRAMS</h2>
    </div>

    <div class="programs-info-card">
        <p>The Bicol University ETEEAP offers the following degree programs:</p>
        <br>
        <ul class="program-bullets">
            <li><strong>BS Information Technology</strong></li>
            <li><strong>BS Nursing</strong></li>
            <li><strong>BS Automotive Technology</strong></li>
            <li><strong>BS Fisheries</strong></li>
            <li><strong>AB Communication</strong></li>
        </ul>
        <br>
        <p class="program-note">Each program is designed to certify your professional experience into an academic degree.</p>
        
        <button class="btn-view-details" onclick="viewPdf('{{ asset('pdf/BU_ETEEAP_Guide.pdf') }}')">
            View Program Details
        </button>
    </div>
  </div>
</section>

<div id="pdfModal" class="pdf-modal">
    <div class="modal-content">
        <span class="close-btn" onclick="closePdf()">&times;</span>
        <iframe id="pdfFrame" src="" frameborder="0"></iframe>
    </div>
</div>
  </div>
</section>

<!-- FAQ SECTION -->
<section id="faq" class="faq">
  <div class="faq-container">

    <!-- LEFT -->
    <div class="faq-left">
      <h2>Frequently ask<br> questions</h2>

      <div class="faq-box">
    <h3>Still have Question?</h3>
    <p>Message us here</p>
    <a href="https://m.me/61569718135798" target="_blank">
    <button class="message-btn">Send us a Message</button>
  </a>
</div>
    </div>

    <!-- RIGHT -->
    <div class="faq-right">

  <div class="faq-item active">
    <div class="faq-header">
      <h4>What is ETEEAP?</h4>
      <span class="faq-toggle">
        <svg class="circle-arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
          <circle cx="12" cy="12" r="12" fill="#2f3c86"/>
          <path d="M8 10l4 4 4-4" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
    </div>
    <p>The Expanded Tertiary Education Equivalency and Accreditation Program (ETEEAP) is an <strong><em>assessment scheme</em></strong> that <strong>allows people to earn a college degree </strong>
     by means of crediting formal and non-formal learning, work-experience, and training.</p>
  </div>

  <div class="faq-item">
    <div class="faq-header">
      <h4>How does it work?</h4>
      <span class="faq-toggle">
        <svg class="circle-arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
          <circle cx="12" cy="12" r="12" fill="#2f3c86"/>
          <path d="M8 10l4 4 4-4" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </span>
    </div>
    <p>Qualified applicants for ETEEAP will have their <strong>prior learning assessed through a portfolio</strong> that shows how their experience match the learning outcomes of the degree program they are applying for. <br><br>
        Depending on the <strong>equvalent credits </strong> they recieve fo their prior learning, they will <strong> complete any remanining course or requirements</strong>needed to earn their academic degree.</p>
  </div>

  <div class="faq-item">
  <div class="faq-header">
    <h4>Who are qualified?</h4>
    <span class="faq-toggle">
      <svg class="circle-arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
        <circle cx="12" cy="12" r="12" fill="#2f3c86"/>
        <path d="M8 10l4 4 4-4" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </span>
  </div>
  <ul class="check-bullets">
    <li>High School Graduates <br> evidenced by a diploma or PEPT/ALS A & E result stating that they are "qualified to enter first year college"</li>
    <li>Filipino citizens <br> whether currently in the Philippines or abroad</li>
    <li> Aged 23 years or older</li>
    <li>With at least <strong> 5 years of aggregate working experience</strong> in the field or discipline they are applying for
  </ul>
</div>

</div>

  </div>
</section>


<section id="contact" class="contact">
  <h2>Get in Touch</h2>
  <p class="contact-sub">Have questions or need assistance? Feel free to reach out to us anytime—we’re here to help.</p>

  <div class="contact-cards">

    <a href="mailto:bu_eteeap@bicol-u.edu.ph" class="contact-card">
      <div class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
      </div>
      <p>bu_eteeap@bicol-u.edu.ph</p>
    </a>

    <a href="https://www.facebook.com/profile.php?id=61569718135798" target="_blank" class="contact-card">
      <div class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
      </div>
      <p>Bicol University-ETEEAP</p>
    </a>

    <a href="https://www.google.com/maps/search/?api=1&query=Bicol+University+Main+Library+Daraga+Albay" target="_blank" class="contact-card">
      <div class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
      </div>
      <p>3rd Floor, University Library Bldg., BU Main Campus, Daraga, Albay</p>
    </a>

  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  © 2026 BU-ETEEAP | All Rights Reserved
</footer>

<script>
const faqItems = document.querySelectorAll('.faq-item');

faqItems.forEach(item => {
  const toggle = item.querySelector('.faq-toggle');

  toggle.addEventListener('click', () => {
    // Close other items
    faqItems.forEach(i => {
      if(i !== item) i.classList.remove('active');
    });

    // Toggle current item
    item.classList.toggle('active');
  });
});

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('mode') === 'signup') {
        document.getElementById('container').classList.add("right-panel-active");
    }
}

const hamburger = document.getElementById('hamburger');
const navLinks = document.getElementById('nav-links');

hamburger.addEventListener('click', () => {
  hamburger.classList.toggle('active');
  navLinks.classList.toggle('active');
});

// Close menu when a link is clicked (useful for one-page sites)
document.querySelectorAll('.nav-links a').forEach(link => {
  link.addEventListener('click', () => {
    hamburger.classList.remove('active');
    navLinks.classList.remove('active');
  });
});


  const btn = document.getElementById("readMoreBtn");
  const content = document.getElementById("moreContent");
  const aboutSection = document.getElementById("about");

  btn.addEventListener("click", () => {
    const isOpen = content.classList.toggle("show");

    if (isOpen) {
      btn.textContent = "Read Less";
      content.appendChild(btn); 
    } else {
      btn.textContent = "Read More";
      content.parentNode.insertBefore(btn, content); 

      aboutSection.scrollIntoView({
        behavior: "smooth"
      });
    }
  });

  function toggleExample() {
    var x = document.getElementById("exampleImages");
    var btn = document.querySelector(".btn-toggle-example");
    
    if (x.style.display === "none") {
        x.style.display = "flex";
        btn.innerHTML = "Hide Example";
    } else {
        x.style.display = "none";
        btn.innerHTML = "See Example Here";
    }
}

function viewPdf(pdfPath) {
    const modal = document.getElementById('pdfModal');
    const frame = document.getElementById('pdfFrame');
    
    frame.src = pdfPath;
    modal.style.display = 'flex';
    
    document.body.style.overflow = 'hidden';
}

function closePdf() {
    const modal = document.getElementById('pdfModal');
    const frame = document.getElementById('pdfFrame');
    
    modal.style.display = 'none';
    frame.src = '';
    
    
    document.body.style.overflow = 'auto';
}

// Close modal if user clicks outside the content
window.onclick = function(event) {
    const modal = document.getElementById('pdfModal');
    if (event.target == modal) {
        closePdf();
    }
}


async function loadFBNews() {
    const myRSSLink = "https://rss.app/feeds/hehF7HW8NbInvS2H.xml"; 
    const API_URL = `https://api.rss2json.com/v1/api.json?rss_url=${encodeURIComponent(myRSSLink)}`;

    try {
        const response = await fetch(API_URL);
        const data = await response.json();
        
        if (data.status === 'ok') {
            const items = data.items;
            const container = document.getElementById('fb-news-container');
            
            const latest = items[0];
            const latestDate = new Date(latest.pubDate);
            const others = items.slice(1, 4);

            let rightItemsHTML = '';
            others.forEach(post => {
                const d = new Date(post.pubDate);
                const postImg = post.thumbnail || (post.enclosure && post.enclosure.link) || '';
                
                rightItemsHTML += `
                    <div class="news-item" onclick="window.open('${post.link}', '_blank')" style="cursor:pointer">
                        <div class="date">
                            ${d.toLocaleString('default', { month: 'short' })}<br>
                            <span>${d.getDate()}</span>
                        </div>
                        <div class="thumb" style="background-image: url('${postImg}');"></div>
                        <div class="text">
                            <h4>${post.title.substring(0, 40)}...</h4>
                            <p>${post.description.replace(/<[^>]*>?/gm, '').substring(0, 60)}...</p>
                        </div>
                    </div>
                `;
            });

            container.innerHTML = `
                <div class="news-left">
                    <div class="big-card" style="background-image: url('${latest.thumbnail || (latest.enclosure && latest.enclosure.link) || ''}');"></div>
                    <div class="small-card">
                        <div class="date">
                            ${latestDate.toLocaleString('default', { month: 'short' })}<br>
                            <span>${latestDate.getDate()}</span>
                        </div>
                        <div class="content">
                            <h3>LATEST UPDATE</h3>
                            <p>${latest.description.replace(/<[^>]*>?/gm, '').substring(0, 120)}...</p>
                            <button onclick="window.open('${latest.link}', '_blank')">Read More</button>
                        </div>
                    </div>
                </div>
                <div class="news-right">
                    ${rightItemsHTML}
                </div>
            `;
        }
    } catch (error) {
        console.error("News Load Error:", error);
    }
}
loadFBNews();

</script>
</body>
</html>
