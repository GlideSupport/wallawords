# GLIDE - WallaWords WordPress Project

This is a WordPress repository configured to run on the [WP Engine platform](https://wpengine.com/support/deploying-code-with-bitbucket-pipelines-wp-engine/).

## Project Overview

WallaWords project is a custom WordPress theme built with NodeJS, Composer, and WebPack. This repository contains the theme code and configuration required for the project.

- **Project Name:** WallaWords
- **Theme Name:** WallaWords
- **Theme Location:** `/wp-content/themes/wallawords`
- **NodeJS Version:** 18.15.0
- **Composer:** Required for theme setup
- **Branches:**
  - `main` (Production)
  - `development` (Development)
- **Production Site:** [https://www.mcashan.com/wallawords-v31/](https://www.mcashan.com/wallawords-v31/)
- **Development Site:** [https://playground1stg.wpengine.com/](https://playground1stg.wpengine.com/)

## Branches

### Development Branch

- **Connected to:** WPE Development Install [playground1stg](https://my.wpengine.com/installs/playground1stg)
- **URL:** [https://playground1stg.wpengine.com/](https://playground1stg.wpengine.com/)
- **Pipeline:** Bitbucket pipeline for deploying code to the development environment

## Setup Instructions

### Prerequisites

- Ensure you have NodeJS version 18.15.0 installed.
- Ensure Composer is installed on your system.

### Initial Setup

1. **Clone the Repository:**

   This is a private repository, so you need to authenticate with your Bitbucket account. Use the following command to clone the repository:

   ```sh
   git clone https://<your-username>@github.com/GlideSupport/wallawords.git
   cd wallawords
   ```

2. **Switch to the Desired Branch:**

   ```sh
   # For development branch
   git checkout development

   # For main branch
   git checkout main
   ```

3. **Setup Theme Dependencies:**

   ```sh
   cd wp-content/themes/wallawords
   npm run setup
   ```

4. **Start Development Server:**

   ```sh
   npm run start
   ```

5. **Build Assets:**

   ```sh
   npm run build
   ```

### Deployment

Deployments are handled automatically through Bitbucket pipelines. Any code pushed to the `development` or `master` branches will be deployed to their respective WPE environments.

## Bitbucket Pipelines

The repository uses Bitbucket Pipelines for CI/CD. The pipeline configuration ensures that code is tested and deployed to the correct environment based on the branch.

### Pipeline Configuration

- **Master Branch:**

  - Deploys to: [https://www.mcashan.com/wallawords-v31/](https://www.mcashan.com/wallawords-v31/)

- **Development Branch:**

  - Deploys to: [https://playground1stg.wpengine.com/](https://playground1stg.wpengine.com/)

---

If you have any further questions or need assistance with specific aspects of the WallaWords project, feel free to ask!
