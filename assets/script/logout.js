const headerLogoutbtn = document.getElementById('header-logout');
const sideMenuLogoutBtn = document.getElementById('side-menu-logout')

function handleLogout() {
    sendAjaxRequest('./functions/shop-logout.php', "POST")
      .then(() => {
        window.location.href = './';
      })
      .catch((error) => {
        console.error("Logout failed:", error);
      });
  }
  
  // Only add listener if the element exists
  if (headerLogoutbtn) headerLogoutbtn.addEventListener('click', handleLogout);
  if (sideMenuLogoutBtn) sideMenuLogoutBtn.addEventListener('click', handleLogout);