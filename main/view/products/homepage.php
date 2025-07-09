<div class="brands-section">
    <h2>Бренды, с которыми работаем</h2>
</div>

<div class="product-images">
    <div class="image-thumbnails">
        <img src="app/pictures/1.png" alt="Brand 1" onclick="setImage(0)">
        <img src="app/pictures/2.png" alt="Brand 2" onclick="setImage(1)">
        <img src="app/pictures/3.png" alt="Brand 3" onclick="setImage(2)">
        <img src="app/pictures/4.png" alt="Brand 4" onclick="setImage(3)">
        <img src="app/pictures/5.png" alt="Brand 5" onclick="setImage(4)">
    </div>
</div>

<style>
    .brands-section {
        text-align: center;
        padding: 20px 0;
        margin-bottom: 20px;
        background-color: #f8f8f8;
    }

    .brands-section h2 {
        color: #333;
        font-size: 24px;
        margin: 0;
    }

    .product-images {
        width: 100%;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .image-thumbnails {
        display: flex;
        justify-content: space-between; /* Равномерное распределение */
        flex-wrap: wrap;
        gap: 15px;
        padding: 15px 0;
    }

    .image-thumbnails img {
        flex: 1; /* Растягиваем элементы */
        min-width: 150px; /* Минимальная ширина */
        max-width: 200px; /* Максимальная ширина */
        height: 100px;
        object-fit: contain; /* Сохраняем пропорции без обрезки */
        cursor: pointer;
        transition: transform 0.3s ease;
        background-color: #fff;
        padding: 10px;
        border-radius: 4px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .image-thumbnails img:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.15);
    }

    @media (max-width: 768px) {
        .image-thumbnails img {
            min-width: 120px;
            height: 80px;
        }
    }
</style>

<script>
    let currentIndex = 0;

    const images = [
        'app/pictures/1.png',
        'app/pictures/2.png',
        'app/pictures/3.png',
        'app/pictures/4.png',
        'app/pictures/5.png'
    ];

    function setImage(index) {
        currentIndex = index;
       
        console.log("Selected image:", images[currentIndex]);

       
    }

 
    window.onload = function() {

    };
</script>
