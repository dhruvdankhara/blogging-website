<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BlogSphere</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
  <link rel="stylesheet" href="./vendor/css/style.css">
  <link rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css"
    integrity="sha512-vebUliqxrVkBy3gucMhClmyQP9On/HAWQdKDXRaAlb/FKuTbxkjPKUyqVOxAcGwFDka79eTF+YXwfke1h3/wfg=="
    crossorigin="anonymous" referrerpolicy="no-referrer" />
  <link rel="stylesheet" href="./vendor/css/main.css">
  <!-- <link rel="stylesheet" href="./vendor/css/variables.css"> -->
  <link rel="stylesheet" href="./vendor/swiper/swiper-bundle.min.css">
  <!-- Load theme last so it can override component styles -->
  <link rel="stylesheet" href="./vendor/css/theme.css?v=2.0">
  <script src="./vendor/js/main.js"></script>
  <link href="./lib/css/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="icon" href="./assets/website_logo-removebg-preview.png" type="image/png">

</head>

<?php
include "connection.php";
session_start();
if (!isset($_SESSION["user_id"])) {
  header("location:./index.php");
}



$user_id = $_SESSION["user_id"];

$query = "SELECT * FROM Users WHERE user_id='$user_id'";
$runquery = mysqli_query($conn, $query);

// print_r($runquery);

$row = mysqli_fetch_assoc($runquery);
if ($row['user_type'] == "admin") {
  header("location:admin/dashboard.php");
}
$name = $row["name"];

?>


<body>
  <?php $active = basename($_SERVER['PHP_SELF']); ?>
  <header class="header_nav">
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-2">
      <div class="container-fluid">
        <a class="navbar-brand d-flex align-items-center" href="./dashboard.php">
          <span class="fw-bold text-brand">Blog<span style="color: #2ecc71;">Sphere</span></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar"
          aria-controls="mainNavbar" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNavbar">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0 nav-pillbar">
            <li class="nav-item"><a class="nav-link <?= $active === 'dashboard.php' ? 'active' : '' ?>"
                href="./dashboard.php">Home</a></li>
            <li class="nav-item"><a class="nav-link <?= $active === 'add_blog.php' ? 'active' : '' ?>"
                href="./add_blog.php">Write Post</a></li>
            <li class="nav-item"><a class="nav-link <?= $active === 'campaign_view_user.php' ? 'active' : '' ?>"
                href="./campaign_view_user.php">Promotion</a></li>
          </ul>

          <div class="d-flex align-items-center">
            <button class="nav-link border-0 bg-transparent d-md-none me-2" type="button" id="searchToggle"
              title="Toggle Search">
              <i class="bi bi-search"></i>
            </button>

            <form class="d-none d-md-block me-3 position-relative" role="search" action="search.php" method="get"
              id="searchForm">
              <div class="input-group input-group-sm">
                <input class="form-control" type="search" name="q" id="desktopSearchInput"
                  placeholder="Search posts... (Ctrl+K)" aria-label="Search"
                  value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>" autocomplete="off">
                <button class="btn" type="submit" id="desktopSearchBtn"
                  style="background-color: #6c757d; border-color: #6c757d; color: white; cursor: not-allowed;" disabled>
                  <i class="bi bi-search"></i>
                </button>
              </div>
              <div class="search-suggestions position-absolute w-100" style="display: none;"></div>
            </form>

            <div class="nav-item dropdown">
              <a class="nav-link d-flex align-items-center p-1" href="#" data-bs-toggle="dropdown" aria-expanded="false"
                aria-label="Open profile menu">
                <img src="<?= $row['image'] ?>" onerror="this.src='assets/default-profile.png'" alt="Profile"
                  class="rounded-circle" width="32" height="32" style="object-fit:cover;">
              </a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                <li class="dropdown-header text-center">
                  <h6 class="fw-bolder text-capitalize mb-0"><?= $name ?></h6>
                </li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center" href="profile.php">
                    <i class="me-3 bi bi-person"></i>
                    <span>My Profile</span>
                  </a>
                </li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center" href="users_post.php">
                    <i class="me-3 bi bi-postcard-heart"></i>
                    <span>My Posts</span>
                  </a>
                </li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center" href="saved_post.php">
                    <i class="me-3 bi bi-star"></i>
                    <span>Saved Post</span>
                  </a>
                </li>
                <li>
                  <hr class="dropdown-divider">
                </li>
                <li>
                  <a class="dropdown-item d-flex align-items-center" href="./logout.php">
                    <i class="me-3 bi bi-box-arrow-right"></i>
                    <span>Sign Out</span>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <!-- Mobile Search Form -->
      <div class="container-fluid d-md-none" id="mobileSearch" style="display: none;">
        <form class="py-2" role="search" action="search.php" method="get">
          <div class="input-group">
            <input class="form-control" type="search" name="q" id="mobileSearchInput" placeholder="Search posts..."
              aria-label="Search" value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
            <button class="btn" type="submit" id="mobileSearchBtn"
              style="background-color: #6c757d; border-color: #6c757d; color: white; cursor: not-allowed;" disabled>
              <i class="bi bi-search"></i>
            </button>
          </div>
        </form>
      </div>
    </nav>
  </header>

  <script src="./vendor/swiper/swiper-bundle.min.js"></script>
  <script src="./lib/js/main.js"></script>
  <script src="./lib/js/bootstrap.bundle.min.js"></script>

  <script>
    // Dynamic search button state management
    function toggleSearchButton(input, button) {
      const hasText = input.value.trim().length > 0;

      if (hasText) {
        // Enable button
        button.disabled = false;
        button.style.backgroundColor = '#2ecc71';
        button.style.borderColor = '#2ecc71';
        button.style.cursor = 'pointer';
        button.title = 'Search';
      } else {
        // Disable button
        button.disabled = true;
        button.style.backgroundColor = '#6c757d';
        button.style.borderColor = '#6c757d';
        button.style.cursor = 'not-allowed';
        button.title = 'Please enter search text';
      }
    }

    // Initialize button states on page load
    document.addEventListener('DOMContentLoaded', function () {
      const desktopInput = document.getElementById('desktopSearchInput');
      const desktopBtn = document.getElementById('desktopSearchBtn');
      const mobileInput = document.getElementById('mobileSearchInput');
      const mobileBtn = document.getElementById('mobileSearchBtn');

      // Set initial states
      if (desktopInput && desktopBtn) {
        toggleSearchButton(desktopInput, desktopBtn);

        // Add event listeners for desktop search
        desktopInput.addEventListener('input', function () {
          toggleSearchButton(this, desktopBtn);
        });

        desktopInput.addEventListener('keyup', function () {
          toggleSearchButton(this, desktopBtn);
        });

        desktopInput.addEventListener('paste', function () {
          setTimeout(() => toggleSearchButton(this, desktopBtn), 10);
        });
      }

      if (mobileInput && mobileBtn) {
        toggleSearchButton(mobileInput, mobileBtn);

        // Add event listeners for mobile search
        mobileInput.addEventListener('input', function () {
          toggleSearchButton(this, mobileBtn);
        });

        mobileInput.addEventListener('keyup', function () {
          toggleSearchButton(this, mobileBtn);
        });

        mobileInput.addEventListener('paste', function () {
          setTimeout(() => toggleSearchButton(this, mobileBtn), 10);
        });
      }
    });

    // Mobile search toggle
    document.getElementById('searchToggle')?.addEventListener('click', function () {
      const mobileSearch = document.getElementById('mobileSearch');
      if (mobileSearch.style.display === 'none' || mobileSearch.style.display === '') {
        mobileSearch.style.display = 'block';
        const mobileInput = mobileSearch.querySelector('input');
        mobileInput.focus();
        // Update button state when mobile search opens
        const mobileBtn = document.getElementById('mobileSearchBtn');
        if (mobileBtn) {
          toggleSearchButton(mobileInput, mobileBtn);
        }
      } else {
        mobileSearch.style.display = 'none';
      }
    });

    // Global keyboard shortcut for search (Ctrl/Cmd + K)
    document.addEventListener('keydown', function (e) {
      if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        const searchInput = document.querySelector('#searchForm input') || document.querySelector('#mobileSearch input');
        if (searchInput) {
          searchInput.focus();
          if (window.innerWidth < 768) {
            document.getElementById('mobileSearch').style.display = 'block';
          }
        }
      }
    });

    // Search suggestions functionality
    let searchTimeout;
    let currentSuggestionIndex = -1;

    document.querySelectorAll('input[name="q"]').forEach(input => {
      const suggestionsContainer = input.closest('form').querySelector('.search-suggestions');

      // Handle input events for suggestions
      input.addEventListener('input', function () {
        const query = this.value.trim();
        currentSuggestionIndex = -1;

        clearTimeout(searchTimeout);

        if (query.length < 2) {
          if (suggestionsContainer) {
            suggestionsContainer.style.display = 'none';
          }
          return;
        }

        searchTimeout = setTimeout(() => {
          fetchSearchSuggestions(query, suggestionsContainer);
        }, 300);
      });

      // Handle keyboard navigation
      input.addEventListener('keydown', function (e) {
        const suggestions = suggestionsContainer?.querySelectorAll('.search-suggestion-item');

        if (e.key === 'ArrowDown') {
          e.preventDefault();
          if (suggestions && suggestions.length > 0) {
            currentSuggestionIndex = Math.min(currentSuggestionIndex + 1, suggestions.length - 1);
            updateSuggestionSelection(suggestions);
          }
        } else if (e.key === 'ArrowUp') {
          e.preventDefault();
          if (suggestions && suggestions.length > 0) {
            currentSuggestionIndex = Math.max(currentSuggestionIndex - 1, -1);
            updateSuggestionSelection(suggestions);
          }
        } else if (e.key === 'Enter') {
          if (currentSuggestionIndex >= 0 && suggestions && suggestions[currentSuggestionIndex]) {
            e.preventDefault();
            suggestions[currentSuggestionIndex].click();
          }
        } else if (e.key === 'Escape') {
          this.blur();
          if (suggestionsContainer) {
            suggestionsContainer.style.display = 'none';
          }
          if (window.innerWidth < 768) {
            document.getElementById('mobileSearch').style.display = 'none';
          }
        }
      });

      // Hide suggestions when clicking outside
      document.addEventListener('click', function (e) {
        if (suggestionsContainer && !input.contains(e.target) && !suggestionsContainer.contains(e.target)) {
          suggestionsContainer.style.display = 'none';
        }
      });

      // Remove the focus/blur animation that was causing color conflicts
      // The dynamic button state management will handle all color changes
    });

    // Fetch search suggestions via AJAX
    function fetchSearchSuggestions(query, container) {
      if (!container) return;

      console.log('Fetching suggestions for:', query);

      fetch(`search_suggestions.php?q=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(suggestions => {
          console.log('Received suggestions:', suggestions);
          displaySearchSuggestions(suggestions, container);
        })
        .catch(error => {
          console.error('Search suggestions error:', error);
          container.style.display = 'none';
        });
    }

    // Display search suggestions
    function displaySearchSuggestions(suggestions, container) {
      if (!container) {
        console.log('No container found');
        return;
      }

      console.log('Displaying suggestions in container:', container);

      if (suggestions.length === 0) {
        container.innerHTML = '<div class="search-suggestion-empty">No suggestions found</div>';
        container.style.display = 'block';
        return;
      }

      let html = '';
      suggestions.forEach((suggestion, index) => {
        // Map suggestion types to appropriate icons
        let iconClass = 'bi-file-earmark-text';
        if (suggestion.type === 'post') {
          iconClass = 'bi-file-earmark-text';
        } else if (suggestion.type === 'category') {
          iconClass = 'bi-tag';
        } else if (suggestion.type === 'author') {
          iconClass = 'bi-person-circle';
        }

        const typeLabel = suggestion.type.charAt(0).toUpperCase() + suggestion.type.slice(1);

        html += `
          <a href="${suggestion.url}" class="search-suggestion-item" data-index="${index}">
            <i class="bi ${iconClass}"></i>
            <div class="suggestion-content">
              <div class="suggestion-title">${escapeHtml(suggestion.title)}</div>
              <div class="suggestion-type">${typeLabel}</div>
            </div>
          </a>
        `;
      });

      console.log('Setting HTML:', html);
      container.innerHTML = html;
      container.style.display = 'block';
      currentSuggestionIndex = -1;
    }

    // Helper function to escape HTML
    function escapeHtml(text) {
      const div = document.createElement('div');
      div.textContent = text;
      return div.innerHTML;
    }

    // Update suggestion selection highlighting
    function updateSuggestionSelection(suggestions) {
      suggestions.forEach((item, index) => {
        item.classList.toggle('active', index === currentSuggestionIndex);
      });
    }
  </script>
</body>

</html>