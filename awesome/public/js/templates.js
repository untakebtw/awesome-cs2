window.defaultsTemplate = (weapon, langObject, lang) => {
    let card = document.createElement("div")
    card.classList.add("weapon-card-wrapper") // Wrapper to maintain grid flow

    const rarityClass = "rarity-common";

    card.innerHTML = `
    <div class="weapon-card ${rarityClass}" id="${weapon.weapon_name}">


        <div class="weapon-card-image">
            <div id="loading-${weapon.weapon_name}" class="loading-spinner">
                <i class="fa-solid fa-circle-notch fa-spin"></i>
            </div>
            <img src="${weapon.image}" class="weapon-img contrast-reset" loading="lazy" alt="${weapon.paint_name}">
        </div>

        <div class="weapon-card-info text-center">
            <p class="weapon-paint-title m-0">Default</p>
            <p class="weapon-skin-title m-0">${weapon.paint_name}</p>
            
            <div class="mt-3">
                <div class="team-selection-group mb-2" id="teams-indicator-${weapon.weapon_name}" ${weapon.both_teams ? '' : 'style="display:none"'}>
                    <input type="checkbox" class="btn-check" id="equip-t-${weapon.weapon_name}" autocomplete="off">
                    <label class="team-btn-equip btn-t" for="equip-t-${weapon.weapon_name}" title="Equip to T loadout">
                        <img class="team-logo" src="/icons/t-logo.png">
                    </label>
                    <input type="checkbox" class="btn-check" id="equip-ct-${weapon.weapon_name}" autocomplete="off">
                    <label class="team-btn-equip btn-ct" for="equip-ct-${weapon.weapon_name}" title="Equip to CT loadout">
                        <img class="team-logo" src="/icons/ct-logo.png">
                    </label>
                </div>
                <button onclick="showSkins('${weapon.weapon_name}')" class="btn btn-primary w-100" id="show-${weapon.weapon_name}-skins-btn">
                    <i class="fas fa-magic me-1"></i> ${langObject.changeSkin}
                </button>
            </div>
        </div>
    </div>
    `

    document.getElementById("skinsContainer").appendChild(card)

    if (weapon.both_teams) {
        let weaponCardBtn = document.getElementById(`show-${weapon.weapon_name}-skins-btn`)
        if (weaponCardBtn) {
            weaponCardBtn.onclick = function () { showSkins(weapon.weapon_name, true) }
        }
    }
}

window.changeSkinTemplate = (weapon, langObject) => {
    let card = document.createElement("div")
    card.classList.add("weapon-card-wrapper")

    card.innerHTML = `
    <div class="weapon-card rarity-common" id="${weapon.weapon_name}">
        <button id="reset-${weapon.weapon_name}" onclick="resetSkin(${weapon.weapon_defindex})" class="revert" title="Reset Skin">
            <i class="fa-solid fa-rotate-right"></i>
        </button>
        <button onclick="openPatternModal('${weapon.weapon_name}')" class="settings" title="Pattern & Float Settings">
            <i class="fa-solid fa-sliders"></i>
        </button>


        <div class="weapon-card-image">
            <div id="loading-${weapon.weapon_name}" class="loading-spinner">
                <i class="fa-solid fa-circle-notch fa-spin"></i>
            </div>
            <img src="${weapon.image}" class="weapon-img contrast-reset" loading="lazy" alt="${weapon.paint_name}" id="img-${weapon.weapon_name}">
        </div>

        <div class="weapon-card-info text-center">
            <p class="weapon-skin-title m-0" id="skin-title-${weapon.weapon_name}"></p>
            <p class="weapon-paint-title m-0" id="weapon-title-${weapon.weapon_name}">${weapon.paint_name}</p>
            
            <div class="mt-3">
                <div class="team-selection-group mb-2" id="teams-indicator-${weapon.weapon_name}" ${weapon.both_teams ? '' : 'style="display:none"'}>
                    <input type="checkbox" class="btn-check" id="equip-t-${weapon.weapon_name}" autocomplete="off">
                    <label class="team-btn-equip btn-t" for="equip-t-${weapon.weapon_name}" title="Equip to T loadout">
                        <img class="team-logo" src="/icons/t-logo.png">
                    </label>
                    <input type="checkbox" class="btn-check" id="equip-ct-${weapon.weapon_name}" autocomplete="off">
                    <label class="team-btn-equip btn-ct" for="equip-ct-${weapon.weapon_name}" title="Equip to CT loadout">
                        <img class="team-logo" src="/icons/ct-logo.png">
                    </label>
                </div>
                <button onclick="showSkins('${weapon.weapon_name}')" class="btn btn-primary w-100" id="show-${weapon.weapon_name}-skins-btn">
                    <i class="fas fa-magic me-1"></i> ${langObject.changeSkin}
                </button>
            </div>
        </div>
    </div>
    `

    document.getElementById("skinsContainer").appendChild(card)

    if (weapon.both_teams) {
        getWeaponSkins("guns", weapon.weapon_defindex)
    }
}

window.changeKnifeSkinTemplate = (knife, langObject) => {
    let card = document.createElement("div")
    card.classList.add("weapon-card-wrapper")

    getWeaponSkins("knives", knife.weapon_defindex)

    card.innerHTML = `
    <div class="weapon-card rarity-gold" id="${knife.weapon_name}">
        <button id="reset-${knife.weapon_name}" onclick="resetSkin(${knife.weapon_defindex})" class="revert">
            <i class="fa-solid fa-rotate-right"></i>
        </button>



        <div class="weapon-card-image">
            <div id="loading-${knife.weapon_name}" class="loading-spinner">
                <i class="fa-solid fa-circle-notch fa-spin"></i>
            </div>
            <img src="${knife.image}" class="weapon-img contrast-reset" loading="lazy" alt="${knife.image}" id="img-${knife.weapon_name}">
        </div>

        <div class="weapon-card-info text-center">
            <p class="weapon-skin-title m-0" id="skin-title-${knife.weapon_name}"></p>
            <p class="weapon-paint-title m-0" id="weapon-title-${knife.weapon_name}">${knife.paint_name}</p>
            
            <div class="mt-3">
                <div class="team-selection-group mb-2" id="teams-indicator-${knife.weapon_name}">
                    <input onclick="changeKnife(\'${knife.weapon_name}\', 2)" type="checkbox" class="btn-check" id="equip-t-${knife.weapon_name}" autocomplete="off">
                    <label class="team-btn-equip btn-t" for="equip-t-${knife.weapon_name}" title="Equip to T loadout">
                        <img class="team-logo" src="/icons/t-logo.png">
                    </label>
                    <input onclick="changeKnife(\'${knife.weapon_name}\', 3)" type="checkbox" class="btn-check" id="equip-ct-${knife.weapon_name}" autocomplete="off">
                    <label class="team-btn-equip btn-ct" for="equip-ct-${knife.weapon_name}" title="Equip to CT loadout">
                        <img class="team-logo" src="/icons/ct-logo.png">
                    </label>
                </div>
                <button onclick="showSkins('${knife.weapon_name}')" class="btn btn-primary w-100">
                    <i class="fas fa-magic me-1"></i> ${langObject.changeSkin}
                </button>
            </div>
        </div>
    </div>
    `

    document.getElementById("skinsContainer").appendChild(card)
}

function getRarityClass(color) {
    const rarities = {
        '#b0c3d9': 'rarity-common',
        '#5e98d9': 'rarity-uncommon',
        '#4b69ff': 'rarity-rare',
        '#8847ff': 'rarity-mythical',
        '#d32ce6': 'rarity-legendary',
        '#eb4b4b': 'rarity-ancient',
        '#e4ae39': 'rarity-gold',
        '#caab05': 'rarity-gold', // for knives
    };
    return rarities[color] || 'rarity-common';
}

window.changeSkinCard = (weapon, selectedSkin) => {
    skinsObject.forEach(skinWeapon => {
        if (weaponIds[skinWeapon.weapon.id] == weapon.weapon_defindex && skinWeapon.paint_index == selectedSkin.weapon_paint_id) {

            if (skinWeapon.category.id == "sfui_invpanel_filter_melee") {
                skinWeapon.rarity.color = "#caab05"
            }

            const weaponCard = document.getElementById(weapon.weapon_name);

            // Remove previous rarity classes
            weaponCard.classList.remove('rarity-common', 'rarity-uncommon', 'rarity-rare', 'rarity-mythical', 'rarity-legendary', 'rarity-ancient', 'rarity-gold');

            // Add new rarity class
            const rarityClass = getRarityClass(skinWeapon.rarity.color);
            weaponCard.classList.add(rarityClass);

            // Update CSS variable specifically for the glow
            weaponCard.style.setProperty('--rarity-color', `var(--${rarityClass})`);

            document.getElementById(`img-${weapon.weapon_name}`).src = skinWeapon.image;

            const skinTitle = document.getElementById(`skin-title-${weapon.weapon_name}`);
            skinTitle.innerHTML = skinWeapon.pattern.name;
            if (skinWeapon.phase != undefined) {
                skinTitle.innerHTML += ` (${skinWeapon.phase})`;
            }
        }
    })
}

window.knivesTemplate = (knife, langObject) => {
    let card = document.createElement("div")
    card.classList.add("col-6", "col-sm-4", "col-md-3", "p-2", "align-self-center")

    card.innerHTML = `
    <div class="weapon-card rarity-gold" id="${knife.weapon_name}">


        <div class="weapon-card-image" onclick="changeKnife(\'${knife.weapon_name}\')">
            <div id="loading-${knife.weapon_name}" class="loading-spinner">
                <i class="fa-solid fa-circle-notch fa-spin"></i>
            </div>
            <img src="${knife.image}" class="weapon-img contrast-reset" loading="lazy" alt="${knife.paint_name}">
        </div>
        
        <div class="weapon-card-info text-center">
            <p class="weapon-skin-title m-0" id="skin-title-${knife.weapon_name}">Default</p>
            <p class="weapon-paint-title m-0">${knife.paint_name}</p>
            
            <div class="mt-3">
                <div class="team-selection-group mb-2">
                    <input onclick="changeKnife(\'${knife.weapon_name}\', 2)" type="checkbox" class="btn-check" id="equip-t-${knife.weapon_name}" autocomplete="off">
                    <label class="team-btn-equip btn-t" for="equip-t-${knife.weapon_name}" title="Equip to T loadout">
                        <img class="team-logo" src="/icons/t-logo.png">
                    </label>
                    <input onclick="changeKnife(\'${knife.weapon_name}\', 3)" type="checkbox" class="btn-check" id="equip-ct-${knife.weapon_name}" autocomplete="off">
                    <label class="team-btn-equip btn-ct" for="equip-ct-${knife.weapon_name}" title="Equip to CT loadout">
                        <img class="team-logo" src="/icons/ct-logo.png">
                    </label>
                </div>
                <button onclick="showSkins(\'${knife.weapon_name}\')" class="btn btn-primary w-100">
                    <i class="fas fa-magic me-1"></i> ${langObject.changeSkin}
                </button>
            </div>
        </div>
    </div>
    `

    document.getElementById("skinsContainer").appendChild(card)
}

window.glovesTemplate = (gloves, langObject) => {
    let card = document.createElement("div")
    card.classList.add("weapon-card-wrapper")

    card.innerHTML = `
    <div class="weapon-card rarity-common" id="${gloves.weapon_name}">


        <div class="weapon-card-image">
            <div id="loading-${gloves.weapon_name}" class="loading-spinner">
                <i class="fa-solid fa-circle-notch fa-spin"></i>
            </div>
            <img src="${gloves.image}" class="weapon-img contrast-reset" loading="lazy" alt="${gloves.paint_name}" style="object-fit: contain; aspect-ratio: 512 / 384;">
        </div>

        <div class="weapon-card-info text-center">
            <p class="weapon-skin-title m-0" id="skin-title-${gloves.weapon_name}">Default</p>
            <p class="weapon-paint-title m-0">${gloves.paint_name}</p>
            
            <div class="mt-3">
                <div class="team-selection-group mb-2" id="teams-indicator-${gloves.weapon_name}">
                    <input type="checkbox" class="btn-check" id="equip-t-${gloves.weapon_name}" autocomplete="off">
                    <label class="team-btn-equip btn-t" for="equip-t-${gloves.weapon_name}" title="Equip to T loadout">
                        <img class="team-logo" src="/icons/t-logo.png">
                    </label>
                    <input type="checkbox" class="btn-check" id="equip-ct-${gloves.weapon_name}" autocomplete="off">
                    <label class="team-btn-equip btn-ct" for="equip-ct-${gloves.weapon_name}" title="Equip to CT loadout">
                        <img class="team-logo" src="/icons/ct-logo.png">
                    </label>
                </div>
                <button onclick="showSkins(\'${gloves.weapon_name}\')" class="btn btn-primary w-100">
                    <i class="fas fa-magic me-1"></i> ${langObject.changeSkin}
                </button>
            </div>
        </div>
    </div>
    `

    document.getElementById("skinsContainer").appendChild(card)

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
}

window.changeGlovesSkinTemplate = (gloves, langObject) => {
    let card = document.createElement("div")
    card.classList.add("weapon-card-wrapper")

    getWeaponSkins("gloves", gloves.weapon_defindex)

    card.innerHTML = `
    <div class="weapon-card rarity-common" id="${gloves.weapon_name}">
        <button id="reset-${gloves.weapon_name}" onclick="resetSkin(${gloves.weapon_defindex})" class="revert">
            <i class="fa-solid fa-rotate-right"></i>
        </button>



        <div class="weapon-card-image">
            <div id="loading-${gloves.weapon_name}" class="loading-spinner">
                <i class="fa-solid fa-circle-notch fa-spin"></i>
            </div>
            <img src="${gloves.image}" class="weapon-img contrast-reset" loading="lazy" alt="${gloves.image}" id="img-${gloves.weapon_name}" style="object-fit: contain; aspect-ratio: 512 / 384;">
        </div>

        <div class="weapon-card-info text-center">
            <p class="weapon-skin-title m-0" id="skin-title-${gloves.weapon_name}"></p>
            <p class="weapon-paint-title m-0" id="weapon-title-${gloves.weapon_name}">${gloves.paint_name}</p>
            
            <div class="mt-3">
                <div class="team-selection-group mb-2" id="teams-indicator-${gloves.weapon_name}">
                    <input onclick="changeGloves(\'${gloves.weapon_name}\', 2)" type="checkbox" class="btn-check" id="equip-t-${gloves.weapon_defindex}" autocomplete="off">
                    <label class="team-btn-equip btn-t" for="equip-t-${gloves.weapon_defindex}" title="Equip to T loadout">
                        <img class="team-logo" src="/icons/t-logo.png">
                    </label>
                    <input onclick="changeGloves(\'${gloves.weapon_name}\', 3)" type="checkbox" class="btn-check" id="equip-ct-${gloves.weapon_defindex}" autocomplete="off">
                    <label class="team-btn-equip btn-ct" for="equip-ct-${gloves.weapon_defindex}" title="Equip to CT loadout">
                        <img class="team-logo" src="/icons/ct-logo.png">
                    </label>
                </div>
                <button onclick="showSkins(\'${gloves.weapon_name}\')" class="btn btn-primary w-100">
                    <i class="fas fa-magic me-1"></i> ${langObject.changeSkin}
                </button>
            </div>
        </div>
    </div>
    `

    document.getElementById("skinsContainer").appendChild(card)
}

window.isEllipsisActive = (element) => {
    const style = window.getComputedStyle(element);
    return (
        style.overflow === "hidden" &&
        style.whiteSpace === "nowrap" &&
        style.textOverflow === "ellipsis" &&
        element.scrollWidth > element.clientWidth
    );
}

window.showAgents = (type) => {
    let team = {
        "ct": 3,
        "t": 2
    }

    document.getElementById("skinsContainer").innerHTML = ""

    agentsObject.forEach(element => {
        if (element.team == team[type]) {
            let rarities = {
                "#b0c3d9": "common",
                "#5e98d9": "uncommon",
                "#4b69ff": "rare",
                "#8847ff": "mythical",
                "#d32ce6": "legendary",
                "#eb4b4b": "ancient",
                "#e4ae39": "contraband"
            }

            let bgColor = "rarity-uncommon"
            let phase = ""
            let active = ""
            let steamid = user.id

            // Make outline if this skin is selected
            if (selectedAgents.agent_t == element.model || selectedAgents.agent_ct == element.model) {
                active = "active-card"
            }

            let card = document.createElement("div")
            card.classList.add("weapon-card-wrapper")

            card.innerHTML = `
                <div onclick="changeAgent(\'${steamid}\', \'${element.model}\', \'${type}\')" id="agent-${element.model}" class="weapon-card ${active} rarity-uncommon pb-2">
                    <div class="weapon-card-image">
                        <div id="loading-${element.model}" class="loading-spinner">
                            <i class="fa-solid fa-circle-notch fa-spin"></i>
                        </div>
                        <img src="${element.image}" class="weapon-img contrast-reset" loading="lazy" alt=" ">
                    </div>
                    
                    <div class="weapon-card-info text-center">
                        <h5 class="weapon-skin-title" id="agent-${element.model}-name">
                            ${element.agent_name}
                        </h5>
                    </div>
                </div>
            `

            document.getElementById("skinsContainer").appendChild(card)

            // Show full agent name in tooltip if text overflows
            let agent = document.getElementById(`agent-${element.model}`)
            let agentName = document.getElementById(`agent-${element.model}-name`)

            if (isEllipsisActive(agentName)) {
                agent.setAttribute("data-bs-toggle", "tooltip")
                agent.setAttribute("data-bs-placement", "bottom")
            }

            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        }
    });
}

window.showMusicKits = () => {
    document.getElementById("skinsContainer").innerHTML = ""

    musicObject.forEach(element => {
        if (element.id.slice(-2) != "st") {
            let bgColor = "rarity-uncommon"
            let active = ""
            let steamid = user.id
            let music_id = element.id.slice(element.id.lastIndexOf("-") + 1)

            if (music_id == selectedMusicKit.music_id) {
                active = "active-card"
            }

            let card = document.createElement("div")
            card.classList.add("weapon-card-wrapper")

            card.innerHTML = `
                <div onclick="changeMusic(\'${steamid}\', \'${music_id}\')" id="music-${music_id}" class="weapon-card rarity-rare ${active} pb-2">
                    <div class="weapon-card-image">
                        <div id="loading-${music_id}" class="loading-spinner">
                            <i class="fa-solid fa-circle-notch fa-spin"></i>
                        </div>
                        <img src="${element.image}" class="weapon-img contrast-reset" loading="lazy" alt=" ">
                    </div>
                    
                    <div class="weapon-card-info text-center">
                        <h5 class="weapon-skin-title" id="music-${music_id}-name">
                            ${element.name.slice(12)}
                        </h5>
                    </div>
                </div>
            `

            document.getElementById("skinsContainer").appendChild(card)

            // Show full music kit name in tooltip if text overflows
            let musicKit = document.getElementById(`music-${music_id}`)
            let musicKitName = document.getElementById(`music-${music_id}-name`)

            if (isEllipsisActive(musicKitName)) {
                musicKit.setAttribute("data-bs-toggle", "tooltip")
                musicKit.setAttribute("data-bs-placement", "bottom")
            }

            const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
            const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
        }
    });

}
