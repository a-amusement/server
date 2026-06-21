(function () {
  async function api(url, options) {
    const res = await fetch(url, options);
    const data = await res.json().catch(() => ({}));
    if (!res.ok || data.ok === false) {
      throw new Error(data.error || 'Request failed');
    }
    return data;
  }

  function updateCount(btn, value) {
    const count = btn.querySelector('.count');
    if (count) {
      count.textContent = String(value);
    }
  }

  function renderComment(comment) {
    const el = document.createElement('div');
    el.className = 'comment-item';
    el.innerHTML = `
      <img src="${comment.avatar_url}" alt="" class="avatar">
      <div class="comment-body">
        <strong>${escapeHtml(comment.display_name || comment.username)}</strong>
        <span class="muted">@${escapeHtml(comment.username)} · ${escapeHtml(comment.time_ago)}</span>
        <p>${escapeHtml(comment.content).replace(/\n/g, '<br>')}</p>
      </div>
    `;
    return el;
  }

  function escapeHtml(str) {
    return String(str)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  document.addEventListener('DOMContentLoaded', () => {
    const composeForm = document.getElementById('compose-form');
    const previewWrap = document.getElementById('compose-preview');
    const imageInput = document.getElementById('post-image');

    if (imageInput && previewWrap) {
      imageInput.addEventListener('change', () => {
        previewWrap.innerHTML = '';
        const file = imageInput.files && imageInput.files[0];
        if (!file) {
          previewWrap.classList.add('hidden');
          return;
        }
        const img = document.createElement('img');
        img.src = URL.createObjectURL(file);
        img.alt = 'Preview';
        previewWrap.appendChild(img);
        previewWrap.classList.remove('hidden');
      });
    }

    if (composeForm) {
      composeForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(composeForm);
        const submitBtn = composeForm.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        try {
          await api('/api/posts.php', { method: 'POST', body: formData });
          window.location.reload();
        } catch (err) {
          alert(err.message);
        } finally {
          submitBtn.disabled = false;
        }
      });
    }

    document.querySelectorAll('.like-btn').forEach((btn) => {
      btn.addEventListener('click', async () => {
        if (btn.disabled) return;
        const postId = btn.dataset.postId;
        try {
          const data = await api('/api/like.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ post_id: Number(postId) }),
          });
          btn.classList.toggle('active', data.liked);
          updateCount(btn, data.likes);
        } catch (err) {
          alert(err.message);
        }
      });
    });

    document.querySelectorAll('.repost-btn').forEach((btn) => {
      btn.addEventListener('click', async () => {
        if (btn.disabled) return;
        const postId = btn.dataset.postId;
        try {
          const data = await api('/api/repost.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ post_id: Number(postId) }),
          });
          btn.classList.toggle('active', data.reposted);
          updateCount(btn, data.reposts);
        } catch (err) {
          alert(err.message);
        }
      });
    });

    document.querySelectorAll('.comment-toggle').forEach((btn) => {
      btn.addEventListener('click', async () => {
        const postId = btn.dataset.postId;
        const panel = document.querySelector(`.comments-panel[data-post-id="${postId}"]`);
        if (!panel) return;
        const opening = panel.classList.contains('hidden');
        panel.classList.toggle('hidden');
        if (opening) {
          const list = panel.querySelector('.comments-list');
          list.innerHTML = '<p class="muted">Loading...</p>';
          try {
            const data = await api(`/api/comments.php?post_id=${postId}`);
            list.innerHTML = '';
            if (!data.comments.length) {
              list.innerHTML = '<p class="muted">No comments yet.</p>';
            } else {
              data.comments.forEach((comment) => list.appendChild(renderComment(comment)));
            }
          } catch (err) {
            list.innerHTML = `<p class="muted">${escapeHtml(err.message)}</p>`;
          }
        }
      });
    });

    document.querySelectorAll('.comment-form').forEach((form) => {
      form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const postId = form.dataset.postId;
        const textarea = form.querySelector('textarea');
        const content = textarea.value.trim();
        if (!content) return;
        const submitBtn = form.querySelector('button[type="submit"]');
        submitBtn.disabled = true;
        try {
          const data = await api('/api/comments.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ post_id: Number(postId), content }),
          });
          textarea.value = '';
          const panel = form.closest('.comments-panel');
          const list = panel.querySelector('.comments-list');
          if (list.querySelector('.muted')) {
            list.innerHTML = '';
          }
          list.appendChild(renderComment(data.comment));
          const toggle = document.querySelector(`.comment-toggle[data-post-id="${postId}"] .count`);
          if (toggle) {
            toggle.textContent = String(data.comments);
          }
        } catch (err) {
          alert(err.message);
        } finally {
          submitBtn.disabled = false;
        }
      });
    });

    document.querySelectorAll('.admin-delete-post').forEach((btn) => {
      btn.addEventListener('click', async () => {
        if (!confirm('Delete this post?')) return;
        const postId = btn.dataset.postId;
        try {
          await api('/api/admin/delete-post.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ post_id: Number(postId) }),
          });
          btn.closest('.post-card')?.remove();
        } catch (err) {
          alert(err.message);
        }
      });
    });
  });
})();
