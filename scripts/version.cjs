const fs = require('fs');
const path = require('path');

function getCurrentVersion() {
    // Read version from functions.php
    const functionsPath = path.join(__dirname, '../theme/style.css');
    const functionsContent = fs.readFileSync(functionsPath, 'utf8');
    const versionMatch = functionsContent.match(/Version:\s*([^\n]+)/);
    return versionMatch ? versionMatch[1] : null;
}

function incrementVersion(version, type = 'patch') {
    const [major, minor, patch] = version.split('.').map(Number);
    switch (type.toLowerCase()) {
        case 'major':
            return `${major + 1}.0.0`;
        case 'minor':
            return `${major}.${minor + 1}.0`;
        case 'patch':
            return `${major}.${minor}.${patch + 1}`;
        default:
            throw new Error('Invalid version increment type. Use: major, minor, or patch');
    }
}

function updateVersionInFile(filePath, currentVersion, newVersion) {
    let content = fs.readFileSync(filePath, 'utf8');

    if (filePath.endsWith('style.css')) {
        content = content.replace(
            /(Version:\s*)[^\n]+/,
            `$1${newVersion}`
        );
    }

    fs.writeFileSync(filePath, content, 'utf8');
}

function updateVersion() {
    const type = process.argv[2] || 'patch';
    const currentVersion = getCurrentVersion();

    if (!currentVersion) {
        console.error('Could not determine current version');
        process.exit(1);
    }

    const newVersion = incrementVersion(currentVersion, type);
    console.log(`Updating version from ${currentVersion} to ${newVersion}`);

    // Update version in style.css
    updateVersionInFile(
        path.join(__dirname, '../theme/style.css'),
        currentVersion,
        newVersion
    );

    console.log('Version updated successfully!');
}

updateVersion();
