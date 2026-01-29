/**
 * Firebase Realtime Database Integration
 * For AdoSistering IoT Project
 */

import { initializeApp, getApps } from 'firebase/app';
import { getDatabase, ref, onValue, set, update, get } from 'firebase/database';

// Firebase configuration will be injected from backend
let firebaseApp = null;
let database = null;

/**
 * Initialize Firebase with configuration
 * @param {Object} config - Firebase configuration object
 */
export function initializeFirebase(config) {
    if (!config || !config.apiKey) {
        console.error('Firebase: Invalid configuration provided');
        return null;
    }

    // Check if already initialized
    if (database) {
        return database;
    }

    // Check if app already exists
    const existingApps = getApps();
    if (existingApps.length > 0) {
        firebaseApp = existingApps[0];
        database = getDatabase(firebaseApp);
        return database;
    }

    try {
        firebaseApp = initializeApp({
            apiKey: config.apiKey,
            authDomain: config.authDomain,
            databaseURL: config.databaseURL,
            projectId: config.projectId,
            storageBucket: config.storageBucket,
            messagingSenderId: config.messagingSenderId,
            appId: config.appId,
        });

        database = getDatabase(firebaseApp);
        return database;
    } catch (error) {
        console.error('Firebase: Initialization error', error);
        return null;
    }
}

/**
 * Get database reference
 * @param {string} path - Database path
 */
export function getRef(path) {
    if (!database) {
        console.error('Firebase: Database not initialized');
        return null;
    }
    return ref(database, path);
}

/**
 * Listen to real-time changes on a path
 * @param {string} path - Database path
 * @param {Function} callback - Callback function when data changes
 */
export function listenToData(path, callback) {
    const dbRef = getRef(path);
    if (!dbRef) return null;

    return onValue(dbRef, (snapshot) => {
        const data = snapshot.val();
        callback(data);
    }, (error) => {
        console.error('Firebase: Listen error', error);
    });
}

/**
 * Get data once from a path
 * @param {string} path - Database path
 */
export async function getData(path) {
    const dbRef = getRef(path);
    if (!dbRef) return null;

    try {
        const snapshot = await get(dbRef);
        return snapshot.val();
    } catch (error) {
        console.error('Firebase: Get data error', error);
        return null;
    }
}

/**
 * Set data at a path
 * @param {string} path - Database path
 * @param {any} data - Data to set
 */
export async function setData(path, data) {
    const dbRef = getRef(path);
    if (!dbRef) return false;

    try {
        await set(dbRef, data);
        return true;
    } catch (error) {
        console.error('Firebase: Set data error', error);
        return false;
    }
}

/**
 * Update data at a path
 * @param {string} path - Database path
 * @param {Object} data - Data to update
 */
export async function updateData(path, data) {
    const dbRef = getRef(path);
    if (!dbRef) return false;

    try {
        await update(dbRef, data);
        return true;
    } catch (error) {
        console.error('Firebase: Update data error', error);
        return false;
    }
}

/**
 * Listen to sensor data for a specific sprayer
 * @param {string} blockName - Block name (e.g., "Block A")
 * @param {string} sprayerName - Sprayer name (e.g., "Sprayer 1")
 * @param {Function} callback - Callback function
 */
export function listenToSprayer(blockName, sprayerName, callback) {
    return listenToData(`MAOS/${blockName}/${sprayerName}`, callback);
}

/**
 * Listen to sensor data for a specific sprayer (legacy)
 * @param {string} sprayerId - Sprayer ID
 * @param {Function} callback - Callback function
 */
export function listenToSensor(sprayerId, callback) {
    return listenToData(`sensors/${sprayerId}`, callback);
}

/**
 * Control relay/pump status (uses number 0/1)
 * @param {string} blockName - Block name (e.g., "Block_A")
 * @param {string} sprayerName - Sprayer name (e.g., "Sprayer_1")
 * @param {boolean} isOn - Pump status (true = ON, false = OFF)
 * @returns {Promise<boolean>} - Success status
 */
export async function controlRelay(blockName, sprayerName, isOn) {
    const relayValue = isOn ? 1 : 0; // Firebase expects 0 or 1 (number)
    return await setData(`MAOS/${blockName}/${sprayerName}/control/relay`, relayValue);
}

/**
 * Get relay status
 * @param {string} blockName - Block name
 * @param {string} sprayerName - Sprayer name
 */
export async function getRelayStatus(blockName, sprayerName) {
    return await getData(`MAOS/${blockName}/${sprayerName}/control/relay`);
}

/**
 * Listen to relay changes
 * @param {string} blockName - Block name
 * @param {string} sprayerName - Sprayer name
 * @param {Function} callback - Callback function
 */
export function listenToRelay(blockName, sprayerName, callback) {
    return listenToData(`MAOS/${blockName}/${sprayerName}/control/relay`, callback);
}

/**
 * Control pump status (legacy - uses boolean)
 * @param {string} sprayerId - Sprayer ID
 * @param {boolean} isOn - Pump status
 */
export async function controlPump(sprayerId, isOn) {
    return await updateData(`controls/${sprayerId}`, {
        pump: isOn,
        timestamp: Date.now()
    });
}

/**
 * Set auto irrigation mode
 * @param {string} sprayerId - Sprayer ID
 * @param {boolean} isAuto - Auto irrigation status
 */
export async function setAutoIrrigation(sprayerId, isAuto) {
    return await updateData(`controls/${sprayerId}`, {
        autoIrrigation: isAuto,
        timestamp: Date.now()
    });
}

/**
 * Listen to entire MAOS structure for dynamic blocks and sprayers
 * @param {Function} callback - Callback with parsed blocks data
 */
export function listenToMAOS(callback) {
    return listenToData('MAOS', (data) => {
        if (!data) {
            callback({ blocks: [] });
            return;
        }

        const blocks = [];

        // Parse Firebase structure: MAOS/{BlockName}/{SprayerName}/...
        Object.keys(data).forEach(blockName => {
            const blockData = data[blockName];
            const sprayers = [];

            // Get all sprayers in this block
            Object.keys(blockData).forEach(sprayerName => {
                const sprayerData = blockData[sprayerName];

                // Parse relay status: 0 = OFF, 1 = ON
                const relayValue = sprayerData?.control?.relay;
                const relayStatus = (relayValue === 1 || relayValue === '1') ? 'ON' : 'OFF';

                // Parse numeric values from Firebase (handle string or number)
                const moisture = parseFloat(sprayerData?.data?.moisture_percent || 0);
                const flowRate = parseFloat(sprayerData?.data?.flow_Lmin || 0);
                const totalVolume = parseFloat(sprayerData?.data?.totalVolume_L || 0);
                const moistureStatus = sprayerData?.data?.moisture_status || 'Kering';

                sprayers.push({
                    name: sprayerName,
                    relay: relayStatus,
                    moisture: moisture,
                    moistureStatus: moistureStatus,
                    flowRate: flowRate,
                    totalVolume: totalVolume,
                    timestamp: sprayerData?.data?.timestamp || 0,
                    arahAngin: sprayerData?.data?.arah_angin || '',
                    kecepatanKmh: parseFloat(sprayerData?.data?.kecepatan_kmh || 0),
                    kecepatanMps: parseFloat(sprayerData?.data?.kecepatan_mps || 0),
                    flowMls: parseFloat(sprayerData?.data?.flow_mLs || 0),
                    sensorConnected: sprayerData?.data?.sensor_connected !== false,
                    autoIrrigation: sprayerData?.control?.autoIrrigation || false,
                });
            });

            // Calculate block averages
            const sprayerCount = sprayers.length || 1;

            // Average moisture percentage
            const avgMoisture = sprayers.reduce((sum, s) => sum + s.moisture, 0) / sprayerCount;

            // Average flow rate (L/min)
            const avgFlowRate = sprayers.reduce((sum, s) => sum + s.flowRate, 0) / sprayerCount;

            // Total volume from all sprayers (L)
            const totalVolume = sprayers.reduce((sum, s) => sum + s.totalVolume, 0);

            // Calculate moisture status badge for block
            // Count how many sprayers are "Lembab" vs "Kering"
            const lembabCount = sprayers.filter(s =>
                s.moistureStatus === 'Lembab' || s.moisture >= 60
            ).length;
            const keringCount = sprayers.filter(s =>
                s.moistureStatus === 'Kering' || s.moisture < 60
            ).length;

            // Majority rule: if more than half are lembab, show "Lembab", otherwise "Kering"
            const blockMoistureStatus = lembabCount > keringCount ? 'Lembab' : 'Kering';

            blocks.push({
                name: blockName,
                sprayers: sprayers,
                avgMoisture: avgMoisture,
                avgFlowRate: avgFlowRate,
                totalVolume: totalVolume,
                moistureStatus: blockMoistureStatus,
            });
        });

        // Sort blocks by name
        blocks.sort((a, b) => a.name.localeCompare(b.name));

        callback({ blocks });
    });
}

// Export all functions for modular usage
const FirebaseModule = {
    initializeFirebase,
    getRef,
    listenToData,
    getData,
    setData,
    updateData,
    listenToSensor,
    listenToSprayer,
    controlPump,
    controlRelay,
    getRelayStatus,
    listenToRelay,
    setAutoIrrigation,
    listenToMAOS,
};

// Expose to window for browser usage (important for dynamic import)
if (typeof window !== 'undefined') {
    window.FirebaseModule = FirebaseModule;
}

export default FirebaseModule;
