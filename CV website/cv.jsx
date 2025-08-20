import { motion } from "framer-motion";
import { useState } from "react";

export default function Portfolio() {
  const [darkMode, setDarkMode] = useState(false);

  const projects = [
    {
      title: "📱 Notes App (Flutter vs React Native)",
      desc: "Dissertation project comparing hybrid mobile frameworks.",
      tech: "Flutter, React Native, Firebase",
      code: "#",
      demo: "#",
    },
    {
      title: "🛒 Online Grocery Store",
      desc: "Full-stack responsive grocery store website with customer & admin features.",
      tech: "PHP, MySQL, React, AJAX",
      code: "#",
      demo: "#",
    },
    {
      title: "🎮 3D Maze Runner Game",
      desc: "A simple 3D maze game built with C++ and openFrameworks.",
      tech: "C++, openFrameworks",
      code: "#",
      demo: "#",
    },
  ];

  return (
    <div className={darkMode ? "bg-gray-900 text-white" : "bg-gray-100 text-gray-900"}>
      {/* Header */}
      <header className="p-6 text-center shadow-md sticky top-0 bg-inherit z-50">
        <h1 className="text-3xl font-bold">Your Name</h1>
        <p className="text-lg">Computer Science Graduate | Software Developer</p>
        <button
          onClick={() => setDarkMode(!darkMode)}
          className="mt-3 px-4 py-2 rounded-xl bg-indigo-600 text-white hover:bg-indigo-700"
        >
          {darkMode ? "☀️ Light Mode" : "🌙 Dark Mode"}
        </button>
      </header>

      {/* About */}
      <section id="about" className="max-w-3xl mx-auto p-8">
        <motion.h2
          className="text-2xl font-semibold mb-4"
          initial={{ opacity: 0, y: 30 }}
          animate={{ opacity: 1, y: 0 }}
        >
          About Me
        </motion.h2>
        <motion.p
          initial={{ opacity: 0 }}
          animate={{ opacity: 1 }}
          transition={{ delay: 0.3 }}
        >
          I am a Computer Science graduate passionate about software development,
          web technologies, and mobile applications. I enjoy building real-world
          projects that combine functionality with clean design.
        </motion.p>
      </section>

      {/* Projects */}
      <section id="projects" className="max-w-5xl mx-auto p-8">
        <h2 className="text-2xl font-semibold mb-6">Projects</h2>
        <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
          {projects.map((proj, i) => (
            <motion.div
              key={i}
              className="bg-white dark:bg-gray-800 rounded-2xl shadow-lg p-5"
              whileHover={{ scale: 1.05 }}
            >
              <h3 className="text-xl font-bold">{proj.title}</h3>
              <p className="mt-2">{proj.desc}</p>
              <p className="mt-2 text-sm text-gray-500 dark:text-gray-400">
                <strong>Tech:</strong> {proj.tech}
              </p>
              <div className="mt-4 flex gap-4">
                <a
                  href={proj.code}
                  className="px-3 py-1 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"
                >
                  View Code
                </a>
                <a
                  href={proj.demo}
                  className="px-3 py-1 bg-gray-700 text-white rounded-lg hover:bg-gray-800"
                >
                  Demo
                </a>
              </div>
            </motion.div>
          ))}
        </div>
      </section>

      {/* Skills */}
      <section id="skills" className="max-w-3xl mx-auto p-8">
        <h2 className="text-2xl font-semibold mb-4">Skills</h2>
        <ul className="grid grid-cols-2 md:grid-cols-3 gap-2">
          <li>💻 C++, JavaScript, PHP, Python</li>
          <li>⚡ Flutter, React, React Native</li>
          <li>🗄️ MySQL, Firebase</li>
          <li>🔧 Git, GitHub, VS Code, Linux</li>
        </ul>
      </section>

      {/* Contact */}
      <section id="contact" className="max-w-3xl mx-auto p-8">
        <h2 className="text-2xl font-semibold mb-4">Contact</h2>
        <p>Email: <a href="mailto:yourname@email.com" className="text-indigo-500">yourname@email.com</a></p>
        <p>LinkedIn: <a href="#" className="text-indigo-500">linkedin.com/in/yourprofile</a></p>
        <p>GitHub: <a href="#" className="text-indigo-500">github.com/yourusername</a></p>
      </section>

      {/* Footer */}
      <footer className="bg-gray-800 text-white text-center py-4 mt-10">
        <p>© 2025 Your Name | Built with React, Tailwind & Framer Motion</p>
      </footer>
    </div>
  );
}
