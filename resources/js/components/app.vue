<template>
    <div>
      <h1>Interview Task</h1>
      <input type="file" @change="uploadImage" accept="image/*" />
      <input type="file" @change="uploadBackground" accept="image/*" />
      <canvas ref="canvas" width="500" height="500"></canvas>
      <button @click="downloadCanvas">Download Work</button>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        image: null,
        background: null,
        images: [] 
      };
    },

    methods: {
      uploadImage(event) {
        const file = event.target.files[0];
        this.loadImage(file, 'image');
      },
      uploadBackground(event) {
        const file = event.target.files[0];
        this.loadImage(file, 'background');
      },
      loadImage(file, type) {
        const reader = new FileReader();
        reader.onload = (e) => {
          const img = new Image();
          img.src = e.target.result;
          img.onload = () => {
            if (type === 'background') {
              this.background = img;
            } else {
              this.images.push(img); 
            }
            this.redrawCanvas(); 
          };
        };
        reader.readAsDataURL(file);
      },

      redrawCanvas() {
        const ctx = this.$refs.canvas.getContext('2d');
        ctx.clearRect(0, 0, this.$refs.canvas.width, this.$refs.canvas.height);
        if (this.background) {
          ctx.drawImage(this.background, 0, 0, this.$refs.canvas.width, this.$refs.canvas.height);
        }
        const gap = 20; 
        this.images.forEach((img, index) => {
          const x = 50 + (100 + gap) * index; 
          ctx.drawImage(img, x, 50, 100, 100); 
        });
      },


      downloadCanvas() {
  const canvas = this.$refs.canvas;
  this.canvasDataUrl = canvas.toDataURL('image/png');  
  const link = document.createElement('a');
  link.href = this.canvasDataUrl;                    
  link.download = 'canvas_work.png';                  
  link.click();     
  this.uploadToServer();                                  
},


async uploadToServer() {
  const formData = new FormData();
  formData.append('canvas_image', this.canvasDataUrl);  

  try {
    const response = await axios.post('http://localhost:8000/api/uploadCanvas', formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    });
    alert('Image uploaded successfully!');
  } catch (error) {
    console.error('Error uploading image:', error);
    alert('Image upload failed!');
  }
},
    },
};
  </script>
  
  <style scoped>
  canvas {
    border: 1px solid black;
  }
  </style>
  
  
  