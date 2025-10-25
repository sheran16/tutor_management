


function editAnnouncement(announcementId) {
   
    const announcementItems = document.querySelectorAll('.announcement-item');
    let announcementText = "";
    
    announcementItems.forEach(item => {
        const editButton = item.querySelector('.btn-edit');
        if (editButton && editButton.getAttribute('onclick').includes(announcementId)) {
            const textElement = item.querySelector('.announcement-text');
            if (textElement) {
                announcementText = textElement.textContent.trim();
            }
        }
    });
    
  
    document.getElementById('editAnnouncementId').value = announcementId;
    document.getElementById('editedText').value = announcementText;
    
  
    document.getElementById('editModal').style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
    
    document.getElementById('editAnnouncementId').value = '';
    document.getElementById('editedText').value = '';
}




document.addEventListener('click', function(event) {
    const modal = document.getElementById('editModal');
    const modalContent = document.querySelector('.modal-content');
    
    if (modal && modalContent && modal.style.display === 'flex') {
        if (event.target === modal) {
            closeEditModal();
        }
    }
});


document.addEventListener('DOMContentLoaded', function() {
    

    const textareas = document.querySelectorAll('textarea');
    textareas.forEach(textarea => {
        textarea.addEventListener('input', function() {
            this.style.height = 'auto';
            this.style.height = this.scrollHeight + 'px';
        });
    });
  
    const popupMessage = document.getElementById('popupMessage');
    if (popupMessage && popupMessage.textContent.trim() !== '') {
        popupMessage.style.display = 'block';
        
        
        setTimeout(function() {
            popupMessage.style.display = 'none';
        }, 5000);
    }
});


document.addEventListener('keydown', function(e) {
   
    if (e.key === 'Escape') {
        const modal = document.getElementById('editModal');
        if (modal && modal.style.display === 'flex') {
            closeEditModal();
        }
        
    }
});


function scrollToAnnouncement(announcementId) {
    const element = document.getElementById('announcement-' + announcementId);
    if (element) {
        element.scrollIntoView({ 
            behavior: 'smooth',
            block: 'center'
        });
        
        element.style.backgroundColor = '#fff3cd';
        setTimeout(function() {
            element.style.backgroundColor = '';
        }, 2000);
    }
}




function formatDate(dateString) {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}


function getTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const diffInSeconds = Math.floor((now - date) / 1000);
    
    if (diffInSeconds < 60) {
        return 'Just now';
    } else if (diffInSeconds < 3600) {
        const minutes = Math.floor(diffInSeconds / 60);
        return minutes + ' minute' + (minutes > 1 ? 's' : '') + ' ago';
    } else if (diffInSeconds < 86400) {
        const hours = Math.floor(diffInSeconds / 3600);
        return hours + ' hour' + (hours > 1 ? 's' : '') + ' ago';
    } else {
        const days = Math.floor(diffInSeconds / 86400);
        return days + ' day' + (days > 1 ? 's' : '') + ' ago';
    }
}



document.addEventListener('DOMContentLoaded', function() {
    
   
    const cards = document.querySelectorAll('.announcement-card, .announcement-item');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
        
        setTimeout(function() {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
