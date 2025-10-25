(function () {
  const modal = document.getElementById('replyModal');
  const feedbackIdInput = document.getElementById('feedback_id');
  const replyTextarea = document.getElementById('tutor_reply');
  // get data & have , open for reply 
  function openModal(feedbackId, currentReply) {
    feedbackIdInput.value = feedbackId;
    replyTextarea.value = currentReply || '';
    modal.classList.add('is-open');
    modal.setAttribute('aria-hidden', 'false');
    setTimeout(() => replyTextarea.focus(), 50);
  }
  // close and hide
  function closeModal() {
    modal.classList.remove('is-open');
    modal.setAttribute('aria-hidden', 'true');
    feedbackIdInput.value = '';
    replyTextarea.value = '';
  }

  // validation for form
  document.getElementById('replyForm').addEventListener('submit', function(e) {
    const reply = replyTextarea.value.trim();
    if (!reply) {
      e.preventDefault();
      alert('Please enter a reply before sending.');
      return false;
    }
    if (reply.length < 5) {
      e.preventDefault();
      alert('Reply must be at least 5 characters long.');
      return false;
    }
    if (!confirm('Are you sure you want to send this reply?')) {
      e.preventDefault();
      return false;
    }
  });

  // confirmation for resolve buttons
  document.querySelectorAll('form').forEach(form => {
    const resolveBtn = form.querySelector('button[name="action"][value="resolve"]');
    if (resolveBtn) {
      form.addEventListener('submit', function(e) {
        if (!confirm('Are you sure you want to mark this feedback as read?')) {
          e.preventDefault();
          return false;
        }
      });
    }
  });

  // Open Reply 
  document.querySelectorAll('.reply-btn').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.getAttribute('data-feedback-id');
      const currentReply = btn.getAttribute('data-current-reply') || '';
      openModal(id, currentReply);
    });
  });
  modal?.addEventListener('click', (e) => {
    if (e.target.matches('[data-close-modal]')) {
      closeModal();
    }
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('is-open')) {
      closeModal();
    }
  });
})();