<?php
$pageTitle = "Contact Us";
require_once __DIR__ . '/includes/header.php';

$messageSent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $messageSent = true;
}
?>

<div class="section" style="padding-top: 3rem; padding-bottom: 5rem;">
  <div style="text-align: center; max-width: 700px; margin: 0 auto 3rem auto;">
    <h1 style="font-size: 2.5rem; font-weight: 800; color: var(--dark); margin-bottom: 0.5rem;">We'd Love to Hear From You</h1>
    <p style="color: var(--gray-600);">Have a question about an order, restaurant partnership, or general inquiry? Send us a message below.</p>
  </div>

  <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 3rem;">
    <div style="background: var(--white); padding: 2rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm);">
      <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 1.5rem; color: var(--dark);">Contact Information</h3>
      
      <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem;">
        <div style="width: 44px; height: 44px; background: var(--primary-light); color: var(--primary); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
          <i class="fas fa-map-marker-alt"></i>
        </div>
        <div>
          <h4 style="font-size: 0.95rem; font-weight: 700;">Headquarters</h4>
          <p style="font-size: 0.9rem; color: var(--gray-600);">100 Tech Blvd, Suite 400<br>Silicon Valley, CA 94025</p>
        </div>
      </div>

      <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem;">
        <div style="width: 44px; height: 44px; background: var(--primary-light); color: var(--primary); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
          <i class="fas fa-phone-alt"></i>
        </div>
        <div>
          <h4 style="font-size: 0.95rem; font-weight: 700;">Customer Care</h4>
          <p style="font-size: 0.9rem; color: var(--gray-600);">+1 800 555 FOOD (3663)<br>Mon-Sun 24/7 Support</p>
        </div>
      </div>

      <div style="display: flex; align-items: flex-start; gap: 1rem;">
        <div style="width: 44px; height: 44px; background: var(--primary-light); color: var(--primary); border-radius: var(--radius-full); display: flex; align-items: center; justify-content: center; font-size: 1.1rem;">
          <i class="fas fa-envelope"></i>
        </div>
        <div>
          <h4 style="font-size: 0.95rem; font-weight: 700;">Email Support</h4>
          <p style="font-size: 0.9rem; color: var(--gray-600);">support@foodhub.com<br>partners@foodhub.com</p>
        </div>
      </div>
    </div>

    <div style="background: var(--white); padding: 2.5rem; border-radius: var(--radius-lg); border: 1px solid var(--gray-200); box-shadow: var(--shadow-sm);">
      <?php if ($messageSent): ?>
        <div style="background: #dcfce7; color: #166534; padding: 1.5rem; border-radius: var(--radius-md); text-align: center; font-weight: 600;">
          <i class="fas fa-check-circle" style="font-size: 2rem; margin-bottom: 0.5rem; display: block;"></i>
          Thank you! Your message has been sent successfully. Our support team will respond within 2 hours.
        </div>
      <?php else: ?>
        <form action="" method="POST" data-validate="true">
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Your Name</label>
              <input type="text" name="name" required style="width: 100%; padding: 0.75rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
            <div>
              <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Email Address</label>
              <input type="email" name="email" required style="width: 100%; padding: 0.75rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
            </div>
          </div>
          <div style="margin-bottom: 1.5rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Subject</label>
            <input type="text" name="subject" required style="width: 100%; padding: 0.75rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;">
          </div>
          <div style="margin-bottom: 2rem;">
            <label style="display: block; font-weight: 600; margin-bottom: 0.5rem; font-size: 0.9rem;">Message</label>
            <textarea name="message" rows="5" required style="width: 100%; padding: 0.75rem 1rem; border-radius: var(--radius-sm); border: 1px solid var(--gray-200); outline: none;"></textarea>
          </div>
          <button type="submit" class="btn btn-primary" style="width: 100%;">
            <i class="fas fa-paper-plane"></i> Send Message
          </button>
        </form>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
