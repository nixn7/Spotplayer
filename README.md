# 🎵 Sounx - Your Music Streaming Hub 🎧

Welcome to Sounx! This project is a web-based music player application designed to help you manage and enjoy your music library. 🎶

## ✨ Overview

Sounx allows users to browse, play, and manage their music collection. It includes features for organizing music by albums and artists, creating playlists, and an admin interface for managing data. The application uses the powerful **getID3** library to extract metadata from audio files.

## 🚀 Features

*   **Browse Music:** 📂 Explore your music library.
*   **Album & Artist Views:** 🖼️ View music organized by albums and artists.
*   **Playlist Management:** 🎼 Create and manage your custom playlists.
*   **Audio Playback:** ▶️ Listen to your favorite tracks.
*   **User Registration & Login:** 👤 Secure access for users.
*   **Admin Panel:** 🛠️ Interface for administrators to manage application data.
*   **Search Functionality:** 🔍 Easily find songs, albums, or artists.
*   **Metadata Extraction:** ℹ️ Uses getID3 to fetch detailed information about audio files.

## 🛠️ Technologies Used

*   **Backend:**
    *   PHP 🐘
*   **Frontend:**
    *   HTML5, CSS3 (including Tailwind CSS 🌬️)
    *   JavaScript (including color-thief for color extraction)
*   **Audio Metadata:**
    *   getID3 Library 🎵
*   **Database:** 
    *   MySQL (via WAMP server)
*   **Development Tools:**
    *   Node.js & NPM (for Tailwind CSS and other dependencies)
    *   PostCSS (for CSS processing)

## 📁 Project Structure

Here's a glimpse into the project's organization:

```
Sounx/
├── admin.php                 # Admin panel
├── album.php                 # Album display page
├── artist.php                # Artist display page
├── browse.php                # Main browsing page
├── createPlaylist.php        # Playlist creation
├── getid3/                   # Core getID3 library files for metadata
├── includes/                 # Reusable PHP scripts and assets
├── index.php                 # Main entry point of the application
├── main.php                  # Core application logic or main user interface
├── playlist.php              # Playlist display page
├── register.php              # User registration
├── search.php                # Search functionality
├── tailwind.config.js        # Tailwind CSS configuration
├── uploads/                  # Directory for uploaded music files
└── ... (other PHP files, CSS, assets)
```

## ⚙️ Setup & Installation

1.  **Clone the Repository:**
    ```bash
    git clone https://github.com/nixn7/Sounx.git
    ```
    
2.  **Server Environment:**
    ```
    Install WAMP, XAMPP, or a similar local server environment with PHP and MySQL.
    ```
    
3.  **Project Setup:**
    ```
    Place the cloned Sounx folder in your web server's root directory (e.g., c:\wamp64\www\)
    ```
    
4.  **Database Setup:**
    *   Create a MySQL database named `Sounx`.
    *   Import the provided `Sounx.sql` file using phpMyAdmin or MySQL command line.
    
5.  **File Permissions:** 
    *   Ensure the `uploads/` directory has appropriate write permissions.
    
6.  **Install Dependencies (if needed):**
    ```bash
    npm install
    ```
    
7.  **Compile CSS (for development):**
    ```bash
    npx tailwindcss -i input.css -o main.css --watch
    ```
    
8.  **Access the application:** 
    ```
    Open your web browser and navigate to http://localhost/Sounx
    ```

## 🔐 Login Credentials

You can use the following pre-configured accounts to access the system:

| Username | Password | Role  |
|----------|----------|-------|
| admin    | admin    | Admin |
| nixn     | 123456   | User  |
| trying1  | trying   | User  |

The admin account provides access to additional features including:
- Adding/editing artists, albums, and songs
- Managing user accounts
- Viewing system statistics

## 🚀 Usage

1.  **Register/Login:** Create an account or log in if you have one.
2.  **Admin Functions:** Use the admin interface to add or edit music data.
3.  **Browse & Play:** Navigate through your library by artists, albums, or songs.
4.  **Create Playlists:** Organize your favorite tracks into custom playlists.
5.  **Search:** Use the search functionality to quickly find specific content.

## 📱 Screenshots

*[Insert screenshots of key pages here]*

## 💡 Future Enhancements

- Mobile responsive design
- Advanced recommendation system
- Social sharing capabilities
- Audio visualization features
- Integration with external music APIs

## 🤝 Contributing

Contributions are welcome! If you'd like to contribute:

1.  Fork the Project 🍴
2.  Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3.  Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4.  Push to the Branch (`git push origin feature/AmazingFeature`)
5.  Open a Pull Request 🔁

---

### 🎧 Happy Listening! 🎵

**Made with ❤️ by Nikhil Shibu**