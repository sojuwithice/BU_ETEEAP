<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
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
<section id="home" class="hero" @if($home->hero_image) style="background-image: url('{{ asset('storage/' . $home->hero_image) }}'); background-size: cover; background-position: center;" @endif>
  <div class="hero-content">
    <h1>
      @if($home->hero_headline)
        {{ $home->hero_headline }} <br><span>{{ $home->hero_highlight ?? '' }}</span>
      @endif
    </h1>
    <a href="{{ route('login', ['mode' => 'signup']) }}" class="apply-btn">Apply Now!</a>
  </div>
</section>

<!-- ABOUT SECTION -->
<section id="about" class="about">
  <div class="about-text">
    <h2>About Us</h2>

    <div class="about-description">
      @if(!empty($home->about_main))
        <div class="about-main">
          {!! $home->about_main !!}
        </div>
      @endif

      @if(!empty($home->about_more))
        <button id="readMoreBtn">Read More</button>

        <div id="moreContent">
          <div class="more-inner">
            {!! $home->about_more !!}
          </div>
        </div>
      @endif
    </div>
  </div>

  @if($home->dean_image || $home->dean_name || $home->dean_title)
    <div class="about-slider">
      <div class="content-wrapper">
        @if($home->dean_image)
          <img src="{{ asset('storage/' . $home->dean_image) }}" alt="Dean">
        @endif
        @if($home->dean_name)
          <h3>{{ $home->dean_name }}</h3>
        @endif
        @if($home->dean_title)
          <p>{{ $home->dean_title }}</p>
        @endif
      </div>
    </div>
  @endif
</section>

<!-- NEWS SECTION -->
<section id="news" class="news">
  <h2 class="news-title">News</h2>
  <div class="news-container" id="fb-news-container">
    <p style="text-align: center; width: 100%;">Loading latest news...</p>
  </div>
</section>

<!-- HOW TO APPLY SECTION -->
<section id="apply" class="apply">
  <div class="apply-header">
    <h2>How to Apply?</h2>
    <p>Please follow the steps below to complete your application process</p>
  </div>

  <div class="steps">
    <!-- ONSITE -->
    <div class="step">
      <h3>Onsite Submission</h3>
    
      <!-- DYNAMIC CONTENT FROM TINY MCE -->
      <div class="rich-text-content">
        {!! $home->apply_on_site ?? 'Default text if empty...' !!}
      </div>

      <!-- ONSITE EXAMPLE IMAGES -->
      <div class="example">
        <div class="example-header">
          <button type="button" class="btn-toggle-example" onclick="toggleExample()">
            See Example Here
          </button>
        </div>

        <div id="exampleImages" class="example-images" style="display: none;">
  @if(!empty($home->apply_example_toc))
    <div class="example-item">
      <img src="{{ asset('storage/' . $home->apply_example_toc) }}" alt="Example">
    </div>
  @endif
  
  @if(!empty($home->apply_example_folder))
    <div class="example-item">
      <img src="{{ asset('storage/' . $home->apply_example_folder) }}" alt="Example">
    </div>
  @endif
</div>
      </div>
    </div>

<div class="step">
  <div class="step-content-wrapper" style="display: flex; align-items: flex-start; justify-content: space-between; gap: 20px;"> 
    
    <div class="text-side" style="flex: 1; min-width: 0; overflow: hidden;"> 
      <h3>Online Submission</h3>
      
      <div class="rich-text-content">
        {!! $home->apply_online ?? 'Default online submission text...' !!}
      </div>

      @if(!empty($home->apply_link))
        <div class="link-wrapper" style="margin-top: 15px;">
          <strong style="display: block; margin-bottom: 5px;">Application Link:</strong>
          <a href="{{ $home->apply_link }}" target="_blank" title="{{ $home->apply_link }}">
            {{ $home->apply_link }}
          </a>
        </div>
      @endif
    </div>

    <div class="qr-side">
          @if(!empty($home->apply_qr))
            <div class="qr-container" style="text-align: center;">
              <img src="{{ asset('storage/' . $home->apply_qr) }}" alt="Online Application QR" style="width: 150px; height: 150px; border: 1px solid #ddd; padding: 5px;">
            </div>
          @else
             <img src="{{ asset('images/default-qr.png') }}" alt="QR Placeholder" style="width: 150px; opacity: 0.5;">
          @endif
        </div>

  </div> 
</div>
  

    <section class="programs-section">
      <div class="programs-title-container">
          <h2 class="programs-title">PROGRAMS</h2>
      </div>

      <div class="programs-info-card">
          <p>The Bicol University ETEEAP offers the following degree programs:</p>
          <br>
          <ul class="program-bullets">
            @php
              $programs = $home->programs ?? ['BS Information Technology', 'BS Nursing', 'BS Automotive Technology', 'BS Fisheries', 'AB Communication'];
            @endphp
            @foreach($programs as $program)
              <li><strong>{{ $program }}</strong></li>
            @endforeach
          </ul>
          <br>
          <p class="program-note">Each program is designed to certify your professional experience into an academic degree.</p>
          
          <button class="btn-view-details" onclick="viewPdf('{{ asset('pdf/BU_ETEEAP_Guide.pdf') }}')">
              View Program Details
          </button>
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

    <div class="faq-right">
      @if($home->faqs && count($home->faqs) > 0)
        @foreach($home->faqs as $index => $faq)
          <div class="faq-item {{ $index == 0 ? 'active' : '' }}">
            <div class="faq-header">
              <h4>{{ $faq['question'] }}</h4>
              <span class="faq-toggle">
                <svg class="circle-arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                  <circle cx="12" cy="12" r="12" fill="#2f3c86"/>
                  <path d="M8 10l4 4 4-4" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
              </span>
            </div>
            <div class="faq-answer">
              {!! $faq['answer'] !!}
            </div>
          </div>
        @endforeach
      @else
        <div class="faq-item active">
          <div class="faq-header">
            <h4>No FAQs available yet</h4>
            <span class="faq-toggle">
              <svg class="circle-arrow" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                <circle cx="12" cy="12" r="12" fill="#2f3c86"/>
                <path d="M8 10l4 4 4-4" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
              </svg>
            </span>
          </div>
          <div class="faq-answer">
            <p>Please check back later for frequently asked questions.</p>
          </div>
        </div>
      @endif
    </div>
  </div>
</section>

<!-- CONTACT SECTION -->
<section id="contact" class="contact">
  <h2>Get in Touch</h2>
  <p class="contact-sub">Have questions or need assistance? Feel free to reach out to us anytime—we’re here to help.</p>

  <div class="contact-cards">
    <!-- EMAIL -->
    @if($home->contact_email)
    <a href="mailto:{{ $home->contact_email }}" class="contact-card">
      <div class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
      </div>
      <p>{{ $home->contact_email }}</p>
    </a>
    @endif

    <!-- FACEBOOK -->
    @if($home->contact_fb)
    <a href="{{ $home->contact_fb }}" target="_blank" class="contact-card">
      <div class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
      </div>
      <p>Bicol University-ETEEAP</p>
    </a>
    @endif

    <!-- ADDRESS / GOOGLE MAPS -->
    @if($home->contact_map)
    <a href="{{ $home->contact_map }}" target="_blank" class="contact-card">
      <div class="icon">
        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/></svg>
      </div>
      <p>{{ $home->contact_address ?? 'Bicol University, Legazpi City' }}</p>
    </a>
    @endif
  </div>
</section>

<!-- FOOTER -->
<footer class="footer">
  © 2026 BU-ETEEAP | All Rights Reserved
</footer>

<script>
// Enhanced FAQ dropdown functionality with better styling support
document.addEventListener('DOMContentLoaded', function() {
  // Initialize FAQ items
  const faqItems = document.querySelectorAll('.faq-item');
  
  faqItems.forEach(item => {
    const toggle = item.querySelector('.faq-toggle');
    const answer = item.querySelector('.faq-answer');
    
    // Set initial display based on active class
    if (answer) {
      answer.style.display = item.classList.contains('active') ? 'block' : 'none';
      // Add some styling for better content display
      answer.style.transition = 'all 0.3s ease';
      
      // Style the inner content from TinyMCE
      const contentElements = answer.querySelectorAll('p, ul, ol, h1, h2, h3, h4, h5, h6');
      contentElements.forEach(el => {
        if (el.tagName === 'P' || el.tagName === 'UL' || el.tagName === 'OL') {
          el.style.marginBottom = '10px';
        }
      });
    }
    
    // Add click event to toggle
    if (toggle) {
      toggle.addEventListener('click', (e) => {
        e.stopPropagation();
        
        // Close all other FAQ items
        faqItems.forEach(i => {
          if (i !== item) {
            i.classList.remove('active');
            const ans = i.querySelector('.faq-answer');
            if (ans) {
              ans.style.display = 'none';
              ans.style.maxHeight = '0';
            }
          }
        });
        
        // Toggle current item
        item.classList.toggle('active');
        if (answer) {
          if (item.classList.contains('active')) {
            answer.style.display = 'block';
            // Smooth scroll to answer if needed
            setTimeout(() => {
              answer.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }, 100);
          } else {
            answer.style.display = 'none';
          }
        }
      });
    }
    
    // Make the entire header clickable for better UX
    const header = item.querySelector('.faq-header');
    if (header) {
      header.style.cursor = 'pointer';
      header.addEventListener('click', () => {
        const fakeEvent = { stopPropagation: () => {} };
        if (toggle) {
          toggle.click();
        }
      });
    }
  });
});


const hamburger = document.getElementById('hamburger');
const navLinks = document.getElementById('nav-links');

hamburger.addEventListener('click', () => {
  hamburger.classList.toggle('active');
  navLinks.classList.toggle('active');
});

document.querySelectorAll('.nav-links a').forEach(link => {
  link.addEventListener('click', () => {
    hamburger.classList.remove('active');
    navLinks.classList.remove('active');
  });
});

const btn = document.getElementById("readMoreBtn");
const content = document.getElementById("moreContent");
const aboutSection = document.getElementById("about");

if (btn && content) {
  btn.addEventListener("click", () => {
    const isOpen = content.classList.toggle("show");

    if (isOpen) {
      btn.textContent = "Read Less";
      if (content.parentNode) {
        content.appendChild(btn);
      }
    } else {
      btn.textContent = "Read More";
      if (content.parentNode) {
        content.parentNode.insertBefore(btn, content);
      }
      aboutSection.scrollIntoView({
        behavior: "smooth"
      });
    }
  });
}

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

window.onclick = function(event) {
  const modal = document.getElementById('pdfModal');
  if (event.target == modal) {
    closePdf();
  }
}

async function loadFBNews() {
  const myRSSLink = "{{ $home->news_rss ?? '' }}"; 
  if (!myRSSLink) {
    console.log("No RSS feed configured");
    return;
  }
  
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