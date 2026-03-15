</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- Professional Footer -->
<footer class="main-footer shenal-footer">
  <div class="footer-inner">

    <!-- Brand Column -->
    <div class="footer-brand">
      <div class="brand-logo">
        <span class="brand-icon">SH</span>
        <span class="brand-name">Shenal Holdings</span>
      </div>
      <p class="brand-tagline">Precision in every invoice. Trust in every transaction.</p>
      <div class="footer-socials">
        <a href="#" title="Facebook"><i class="fa fa-facebook"></i></a>
        <a href="#" title="LinkedIn"><i class="fa fa-linkedin"></i></a>
        <a href="#" title="Twitter"><i class="fa fa-twitter"></i></a>
        <a href="#" title="Email"><i class="fa fa-envelope"></i></a>
      </div>
    </div>

    <!-- Quick Links -->
    <div class="footer-links">
      <h4 class="footer-heading">Quick Links</h4>
      <ul>
        <li><a href="#"><i class="fa fa-angle-right"></i> Dashboard</a></li>
        <li><a href="#"><i class="fa fa-angle-right"></i> Invoices</a></li>
        <li><a href="#"><i class="fa fa-angle-right"></i> Clients</a></li>
        <li><a href="#"><i class="fa fa-angle-right"></i> Reports</a></li>
      </ul>
    </div>

    <!-- Support -->
    <div class="footer-links">
      <h4 class="footer-heading">Support</h4>
      <ul>
        <li><a href="#"><i class="fa fa-angle-right"></i> Help Center</a></li>
        <li><a href="#"><i class="fa fa-angle-right"></i> Privacy Policy</a></li>
        <li><a href="#"><i class="fa fa-angle-right"></i> Terms of Service</a></li>
        <li><a href="#"><i class="fa fa-angle-right"></i> Contact Us</a></li>
      </ul>
    </div>

    <!-- Contact Info -->
    <div class="footer-contact">
      <h4 class="footer-heading">Get In Touch</h4>
      <ul>
        <li><i class="fa fa-map-marker"></i> Shenal Holdings, No.61, Halgasthota Awariwatta, Katunayake, Sri Lanka</li>
        <li><i class="fa fa-phone"></i> +94 75 600 8484</li>
        <li><i class="fa fa-envelope"></i> fernandonishal4@gmail.com</li>
        <li><i class="fa fa-globe"></i> www.shenalholdings.com</li>
      </ul>
    </div>

  </div>

  <!-- Bottom Bar -->
  <div class="footer-bottom">
    <div class="footer-bottom-inner">
      <span class="copy">
        &copy; <?php echo date('Y'); ?> <strong>Shenal Holdings</strong>. All rights reserved.
      </span>
      <span class="powered">
        Crafted with <i class="fa fa-heart text-danger"></i> by Shenal Holdings Team
      </span>
    </div>
  </div>
</footer>

<style>
  .shenal-footer {
    background: linear-gradient(135deg, #1a1f2e 0%, #16213e 60%, #0f172a 100%);
    color: #cbd5e1;
    padding: 0;
    margin-top: auto;
    border-top: 3px solid #3b82f6;
    font-family: 'Segoe UI', sans-serif;
  }

  .footer-inner {
    display: flex;
    flex-wrap: wrap;
    gap: 24px;                  /* ⬇ was 40px */
    padding: 22px 40px 18px;   /* ⬇ was 48px 48px 32px */
    max-width: 1400px;
    margin: 0 auto;
    align-items: flex-start;
  }

  /* Brand */
  .footer-brand {
    flex: 1 1 200px;
  }

  .brand-logo {
    display: flex;
    align-items: center;
    gap: 8px;                   /* ⬇ was 10px */
    margin-bottom: 8px;         /* ⬇ was 14px */
  }

  .brand-icon {
    width: 30px;                /* ⬇ was 40px */
    height: 30px;               /* ⬇ was 40px */
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: #fff;
    font-size: 15px;            /* ⬇ was 20px */
    font-weight: 800;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 14px rgba(59,130,246,0.4);
  }

  .brand-name {
    font-size: 17px;            /* ⬇ was 22px */
    font-weight: 700;
    color: #f1f5f9;
    letter-spacing: 0.5px;
  }

  .brand-tagline {
    font-size: 11.5px;          /* ⬇ was 13px */
    color: #94a3b8;
    line-height: 1.5;
    margin-bottom: 12px;        /* ⬇ was 20px */
  }

  .footer-socials a {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 28px;                /* ⬇ was 34px */
    height: 28px;               /* ⬇ was 34px */
    background: rgba(255,255,255,0.07);
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 6px;
    color: #94a3b8;
    margin-right: 6px;
    text-decoration: none;
    transition: all 0.25s ease;
    font-size: 11px;            /* ⬇ was 13px */
  }

  .footer-socials a:hover {
    background: #3b82f6;
    border-color: #3b82f6;
    color: #fff;
    transform: translateY(-2px);
  }

  /* Links */
  .footer-links, .footer-contact {
    flex: 1 1 140px;
  }

  .footer-heading {
    font-size: 11px;            /* ⬇ was 13px */
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1.5px;
    color: #3b82f6;
    margin-bottom: 10px;        /* ⬇ was 18px */
    padding-bottom: 7px;        /* ⬇ was 10px */
    border-bottom: 1px solid rgba(59,130,246,0.2);
  }

  .footer-links ul,
  .footer-contact ul {
    list-style: none;
    padding: 0;
    margin: 0;
  }

  .footer-links ul li,
  .footer-contact ul li {
    margin-bottom: 6px;         /* ⬇ was 10px */
    font-size: 12px;            /* ⬇ was 13.5px */
    color: #94a3b8;
  }

  .footer-links ul li a {
    color: #94a3b8;
    text-decoration: none;
    transition: color 0.2s, padding-left 0.2s;
  }

  .footer-links ul li a:hover {
    color: #60a5fa;
    padding-left: 4px;
  }

  .footer-links ul li a i,
  .footer-contact ul li i {
    margin-right: 6px;
    color: #3b82f6;
    font-size: 11px;
  }

  /* Bottom Bar */
  .footer-bottom {
    border-top: 1px solid rgba(255,255,255,0.06);
    background: rgba(0,0,0,0.2);
  }

  .footer-bottom-inner {
    max-width: 1400px;
    margin: 0 auto;
    padding: 10px 40px;         /* ⬇ was 16px 48px */
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    font-size: 11.5px;          /* ⬇ was 13px */
    color: #64748b;
  }

  .footer-bottom-inner strong {
    color: #93c5fd;
  }

  .powered .fa-heart {
    font-size: 11px;
    animation: heartbeat 1.4s ease infinite;
  }

  @keyframes heartbeat {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.3); }
  }

  /* Responsive */
  @media (max-width: 768px) {
    .footer-inner {
      padding: 16px 20px 14px;
      gap: 18px;
    }
    .footer-bottom-inner {
      padding: 10px 20px;
      flex-direction: column;
      text-align: center;
    }
  }
</style>

</div>
<!-- ./wrapper -->
</body>
</html>