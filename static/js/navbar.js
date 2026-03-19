(function () {
  "use strict";

  var ACTIVE_DROPDOWN_CLASS = "is-open";
  var SCROLLED_CLASS = "is-scrolled";
  var MOBILE_MENU_OPEN_CLASS = "mobile-menu-open";

function isTouchDevice() {
  return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
}

  function getCurrentPageName() {
    var path = window.location.pathname;
    var page = path.substring(path.lastIndexOf("/") + 1);
    return page || "quienes-somos.html";
  }

  function normalizeHash(hash) {
    if (!hash) {
      return "";
    }
    return hash.charAt(0) === "#" ? hash : "#" + hash;
  }

  function samePage(page) {
    return page === getCurrentPageName();
  }

  function scrollToHash(hash, behavior) {
    var target = resolveHashTarget(hash);
    if (!target) {
      return;
    }

    var header = document.querySelector("[data-site-header]");
    var offset = (header ? header.offsetHeight : 0) + 12;
    var y = target.getBoundingClientRect().top + window.pageYOffset - offset;

    window.scrollTo({
      top: y,
      behavior: behavior || "smooth"
    });
  }

  function resolveHashTarget(hash) {
    var targetHash = normalizeHash(hash);
    if (!targetHash) {
      return null;
    }

    var rawId = targetHash.slice(1);
    var decodedId = rawId;

    try {
      decodedId = decodeURIComponent(rawId);
    } catch (error) {
      decodedId = rawId;
    }

    var candidates = [decodedId, rawId];
    for (var i = 0; i < candidates.length; i += 1) {
      var candidate = candidates[i];
      if (!candidate) {
        continue;
      }

      var byId = document.getElementById(candidate);
      if (byId) {
        return byId;
      }
    }

    try {
      return document.querySelector(targetHash);
    } catch (error) {
      return null;
    }
  }

  function navigateTo(page, hash) {
    var targetHash = normalizeHash(hash);
    var targetPage = page || "quienes-somos.html";

    if (samePage(targetPage)) {
      if (targetHash) {
        scrollToHash(targetHash, "smooth");
        window.history.replaceState(null, "", targetHash);
      } else {
        window.history.replaceState(null, "", targetPage);
        window.scrollTo({ top: 0, behavior: "smooth" });
      }
      return;
    }

    window.location.href = targetHash ? targetPage + targetHash : targetPage;
  }

  function createSubmenuLinks(children, mobileVersion) {
    var fragment = document.createDocumentFragment();

    children.forEach(function (child) {
      var link = document.createElement("a");
      link.className = mobileVersion ? "site-navbar__mobile-sublink" : "site-navbar__sublink";
      link.href = child.page + (child.hash || "");
      link.textContent = child.label;
      link.dataset.page = child.page;
      link.dataset.hash = child.hash || "";
      fragment.appendChild(link);
    });

    return fragment;
  }

  function renderDesktopMenu(items, currentPage) {
    var list = document.createElement("ul");
    list.className = "site-navbar__menu";

    items.forEach(function (item, index) {
      var hasChildren = Array.isArray(item.children) && item.children.length > 0;
      var listItem = document.createElement("li");
      listItem.className = "site-navbar__item" + (hasChildren ? " has-children" : "");
      listItem.dataset.dropdownItem = "true";

      var parentLink = document.createElement("a");
      parentLink.className = "site-navbar__link";
      parentLink.href = item.page;
      parentLink.textContent = item.label;
      parentLink.dataset.page = item.page;
      parentLink.dataset.hash = "";

      if (item.page === currentPage) {
        parentLink.classList.add("is-current");
        parentLink.setAttribute("aria-current", "page");
      }

      listItem.appendChild(parentLink);

      if (hasChildren) {
        var trigger = document.createElement("button");
        var menuId = "site-submenu-" + index;
        trigger.type = "button";
        trigger.className = "site-navbar__trigger";
        trigger.setAttribute("aria-expanded", "false");
        trigger.setAttribute("aria-controls", menuId);
        trigger.setAttribute("aria-label", "Abrir submenu de " + item.label);
        trigger.dataset.dropdownTrigger = "true";
        trigger.innerHTML = '<span class="site-navbar__trigger-icon" aria-hidden="true">+</span>';

        var panel = document.createElement("div");
        panel.className = "site-navbar__dropdown";
        panel.id = menuId;
        panel.hidden = true;

        var scrollArea = document.createElement("div");
        scrollArea.className = "site-navbar__dropdown-scroll";
        scrollArea.appendChild(createSubmenuLinks(item.children, false));

        panel.appendChild(scrollArea);
        listItem.appendChild(trigger);
        listItem.appendChild(panel);
      }

      list.appendChild(listItem);
    });

    return list;
  }

  function renderMobileMenu(items, currentPage) {
    var drawer = document.createElement("div");
    drawer.className = "site-navbar__drawer";
    drawer.setAttribute("aria-hidden", "true");
    drawer.hidden = true;

    var nav = document.createElement("nav");
    nav.className = "site-navbar__drawer-nav";
    nav.setAttribute("aria-label", "Navegacion movil");

    items.forEach(function (item, index) {
      var hasChildren = Array.isArray(item.children) && item.children.length > 0;
      var group = document.createElement("div");
      group.className = "site-navbar__mobile-group" + (hasChildren ? " has-children" : "");

      var row = document.createElement("div");
      row.className = "site-navbar__mobile-row";

      var link = document.createElement("a");
      link.className = "site-navbar__mobile-link";
      link.href = item.page;
      link.textContent = item.label;
      link.dataset.page = item.page;
      link.dataset.hash = "";

      if (item.page === currentPage) {
        link.classList.add("is-current");
        link.setAttribute("aria-current", "page");
      }

      row.appendChild(link);

      if (hasChildren) {
        var groupId = "site-mobile-group-" + index;
        var toggle = document.createElement("button");
        toggle.type = "button";
        toggle.className = "site-navbar__mobile-toggle-group";
        toggle.dataset.mobileGroupTrigger = "true";
        toggle.dataset.groupLabel = item.label;
        toggle.setAttribute("aria-expanded", "false");
        toggle.setAttribute("aria-controls", groupId);
        toggle.setAttribute("aria-label", "Expandir submenu de " + item.label);
        toggle.innerHTML = '<span aria-hidden="true">+</span>';
        row.appendChild(toggle);

        var submenu = document.createElement("div");
        submenu.id = groupId;
        submenu.className = "site-navbar__mobile-submenu";
        submenu.hidden = true;
        submenu.appendChild(createSubmenuLinks(item.children, true));
        group.appendChild(row);
        group.appendChild(submenu);
      } else {
        group.appendChild(row);
      }

      nav.appendChild(group);
    });

    drawer.appendChild(nav);
    return drawer;
  }

  function renderNavbar(config) {
    var mount = document.getElementById("site-navbar");
    if (!mount || !config || !Array.isArray(config.items)) {
      return null;
    }

    var currentPage = getCurrentPageName();
    var root = document.createElement("div");
    root.className = "site-navbar";

    var bar = document.createElement("div");
    bar.className = "site-navbar__bar";

    var brand = document.createElement("a");
    brand.className = "site-navbar__brand";
    brand.href = "quienes-somos.html";
    brand.setAttribute("aria-label", "Ir a la seccion principal de Artricenter");
    brand.innerHTML = '<img src="assets/logo.png" alt="Artricenter" />';

    var desktopNav = document.createElement("nav");
    desktopNav.className = "site-navbar__desktop";
    desktopNav.setAttribute("aria-label", "Navegacion principal");
    desktopNav.appendChild(renderDesktopMenu(config.items, currentPage));

    var mobileToggle = document.createElement("button");
    mobileToggle.type = "button";
    mobileToggle.className = "site-navbar__mobile-toggle";
    mobileToggle.setAttribute("aria-expanded", "false");
    mobileToggle.setAttribute("aria-label", "Abrir menu");
    mobileToggle.innerHTML = '<span></span><span></span><span></span>';

    bar.appendChild(brand);
    bar.appendChild(desktopNav);
    bar.appendChild(mobileToggle);
    root.appendChild(bar);
    root.appendChild(renderMobileMenu(config.items, currentPage));

    mount.innerHTML = "";
    mount.appendChild(root);

    return root;
  }

  function closeAllDropdowns(navRoot) {
    navRoot.querySelectorAll("[data-dropdown-item='true']").forEach(function (item) {
      var trigger = item.querySelector("[data-dropdown-trigger='true']");
      var panel = item.querySelector(".site-navbar__dropdown");
      item.classList.remove(ACTIVE_DROPDOWN_CLASS);
      if (trigger) {
        trigger.setAttribute("aria-expanded", "false");
      }
      if (panel) {
        panel.hidden = true;
      }
    });
  }

  function closeMobileDrawer(navRoot) {
    var drawer = navRoot.querySelector(".site-navbar__drawer");
    var toggle = navRoot.querySelector(".site-navbar__mobile-toggle");
    if (!drawer || !toggle) {
      return;
    }

    navRoot.classList.remove(MOBILE_MENU_OPEN_CLASS);
    drawer.setAttribute("aria-hidden", "true");
    drawer.hidden = true;
    toggle.setAttribute("aria-expanded", "false");
    toggle.setAttribute("aria-label", "Abrir menu");

    navRoot.querySelectorAll("[data-mobile-group-trigger='true']").forEach(function (groupToggle) {
      var contentId = groupToggle.getAttribute("aria-controls");
      var content = contentId ? document.getElementById(contentId) : null;
      groupToggle.setAttribute("aria-expanded", "false");
      setMobileToggleGroupLabel(groupToggle, false);
      if (content) {
        content.hidden = true;
      }
    });
  }

  function setMobileToggleGroupLabel(toggle, expanded) {
    var groupLabel = toggle.dataset.groupLabel || "esta seccion";
    var action = expanded ? "Colapsar" : "Expandir";
    toggle.setAttribute("aria-label", action + " submenu de " + groupLabel);
  }

  function toggleDropdown(item, forceOpen) {
    var trigger = item.querySelector("[data-dropdown-trigger='true']");
    var panel = item.querySelector(".site-navbar__dropdown");
    if (!trigger || !panel) {
      return;
    }

    var shouldOpen = typeof forceOpen === "boolean" ? forceOpen : !item.classList.contains(ACTIVE_DROPDOWN_CLASS);
    item.classList.toggle(ACTIVE_DROPDOWN_CLASS, shouldOpen);
    panel.hidden = !shouldOpen;
    trigger.setAttribute("aria-expanded", shouldOpen ? "true" : "false");
  }

  function bindNavigation(navRoot) {
    navRoot.addEventListener("click", function (event) {
      var trigger = event.target.closest("[data-dropdown-trigger='true']");
      if (trigger) {
        event.preventDefault();
        var item = trigger.closest("[data-dropdown-item='true']");
        if (!item) {
          return;
        }
        var isOpen = item.classList.contains(ACTIVE_DROPDOWN_CLASS);
        closeAllDropdowns(navRoot);
        toggleDropdown(item, !isOpen);
        return;
      }

      var mobileGroupTrigger = event.target.closest("[data-mobile-group-trigger='true']");
      if (mobileGroupTrigger) {
        event.preventDefault();
        var expanded = mobileGroupTrigger.getAttribute("aria-expanded") === "true";
        var contentId = mobileGroupTrigger.getAttribute("aria-controls");
        var content = contentId ? document.getElementById(contentId) : null;
        mobileGroupTrigger.setAttribute("aria-expanded", expanded ? "false" : "true");
        setMobileToggleGroupLabel(mobileGroupTrigger, !expanded);
        if (content) {
          content.hidden = expanded;
        }
        return;
      }

      var menuToggle = event.target.closest(".site-navbar__mobile-toggle");
      if (menuToggle) {
        event.preventDefault();
        var isExpanded = menuToggle.getAttribute("aria-expanded") === "true";
        var nextExpanded = !isExpanded;
        navRoot.classList.toggle(MOBILE_MENU_OPEN_CLASS, !isExpanded);
        menuToggle.setAttribute("aria-expanded", nextExpanded ? "true" : "false");
        menuToggle.setAttribute("aria-label", nextExpanded ? "Cerrar menu" : "Abrir menu");
        var drawer = navRoot.querySelector(".site-navbar__drawer");
        if (drawer) {
          drawer.hidden = !nextExpanded;
          drawer.setAttribute("aria-hidden", nextExpanded ? "false" : "true");
        }
        return;
      }

      var overlay = event.target.closest(".site-navbar__drawer");
      if (overlay && !event.target.closest(".site-navbar__drawer-nav")) {
        event.preventDefault();
        closeMobileDrawer(navRoot);
        return;
      }

      var link = event.target.closest("a[data-page]");
      if (!link) {
        return;
      }

      event.preventDefault();
      navigateTo(link.dataset.page, link.dataset.hash);
      closeAllDropdowns(navRoot);
      closeMobileDrawer(navRoot);
    });

    document.addEventListener("click", function (event) {
      if (!event.target.closest(".site-navbar__desktop")) {
        closeAllDropdowns(navRoot);
      }
    });

    document.addEventListener("keydown", function (event) {
      if (event.key === "Escape") {
        closeAllDropdowns(navRoot);
        closeMobileDrawer(navRoot);
      }
    });

    navRoot.querySelectorAll("[data-dropdown-item='true']").forEach(function (item) {
      if (!item.querySelector("[data-dropdown-trigger='true']")) {
        return;
      }

      item.addEventListener("focusin", function () {
        closeAllDropdowns(navRoot);
        toggleDropdown(item, true);
      });

      item.addEventListener("focusout", function (event) {
        if (item.contains(event.relatedTarget)) {
          return;
        }
        toggleDropdown(item, false);
      });
    });

    window.addEventListener("resize", function () {
      if (window.innerWidth >= 768) {
        closeMobileDrawer(navRoot);
      }
    });
  }

  function bindStickyHeaderState() {
    var header = document.querySelector("[data-site-header]");
    if (!header) {
      return;
    }

    function updateState() {
      header.classList.toggle(SCROLLED_CLASS, window.scrollY > 10);
    }

    updateState();
    window.addEventListener("scroll", updateState, { passive: true });
  }

  function ensureCtaContainer() {
    var existing = document.getElementById("site-cta-container");
    if (existing) {
      return existing;
    }

    var header = document.querySelector("[data-site-header]");
    if (!header || !header.parentNode) {
      return null;
    }

    var container = document.createElement("div");
    container.id = "site-cta-container";
    header.parentNode.insertBefore(container, header.nextSibling);
    return container;
  }

  function renderCtas(config) {
    if (!config || !config.ctas) {
      return;
    }

    var container = ensureCtaContainer();
    if (!container) {
      return;
    }

    container.innerHTML = "";

    if (config.ctas.edith && config.ctas.edith.href && config.ctas.edith.label) {
      var edith = document.createElement("a");
      edith.className = "site-floating-cta site-floating-cta--edith";
      edith.href = config.ctas.edith.href;
      edith.textContent = config.ctas.edith.label;
      edith.setAttribute("aria-label", config.ctas.edith.label);
      container.appendChild(edith);
    }

    if (config.ctas.whatsapp && config.ctas.whatsapp.href && config.ctas.whatsapp.label) {
      var whatsapp = document.createElement("a");
      whatsapp.className = "site-floating-cta site-floating-cta--whatsapp";
      whatsapp.href = config.ctas.whatsapp.href;
      whatsapp.textContent = config.ctas.whatsapp.label;
      whatsapp.target = "_blank";
      whatsapp.rel = "noopener noreferrer";
      whatsapp.setAttribute("aria-label", config.ctas.whatsapp.label);
      container.appendChild(whatsapp);
    }
  }

  function handleInitialHashScroll() {
    if (!window.location.hash) {
      return;
    }

    window.requestAnimationFrame(function () {
      window.requestAnimationFrame(function () {
        scrollToHash(window.location.hash, "smooth");
      });
    });
  }

  function initNavbar() {
    var config = window.ARTRICENTER_NAV;
    var navRoot = renderNavbar(config);
    if (!navRoot) {
      return;
    }

    bindNavigation(navRoot);
    bindStickyHeaderState();
    renderCtas(config);
    handleInitialHashScroll();
  }

  document.addEventListener("DOMContentLoaded", initNavbar);
})();
