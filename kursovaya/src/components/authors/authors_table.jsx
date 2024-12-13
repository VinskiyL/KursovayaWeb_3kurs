import axios from 'axios';
import React, { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';

const Authors = () => {
    const [query, setQuery] = useState('id,author_surname,author_name,author_patronymic kursovaya."Authors_catalog"');
    const [results, setResults] = useState([]);
    const [error, setError] = useState('');
    const [searchTerm, setSearchTerm] = useState(''); // Состояние для поиска

    useEffect(() => {
        const fetchData = async () => {
            setError('');
            try {
                const response = await axios.get(`https://kursovaya.local/select.php`, {
                    params: { query },
                    headers: {
                        'Content-Type': 'application/json',
                    },
                });
                setResults(response.data || []);
            } catch (err) {
                setError('Произошла ошибка при поиске.');
                console.error('Ошибка при выполнении запроса:', err.response ? err.response.data : err.message);
            }
        };
        fetchData();
    }, [query]); // Добавляем query в зависимости, чтобы перезапрашивать данные при изменении

    // Функция для обработки ввода в поле поиска
    const handleSearchChange = (event) => {
        setSearchTerm(event.target.value);
    };

    // Фильтрация результатов на основе searchTerm
    const filteredResults = results.filter(author =>
        author.author_surname.toLowerCase().includes(searchTerm.toLowerCase())
    );

    return (
        <div>
            <input
                type="text"
                placeholder="Поиск по фамилии автора..."
                value={searchTerm}
                onChange={handleSearchChange}
            />
            {filteredResults.length > 0 ? (
                filteredResults.map((author) => (
                    <div key={author.id}>
                        <h3>{author.author_surname}</h3>
                        <h3>{author.author_name}</h3>
                        <h3>{author.author_patronymic}</h3>
                        <Link to={`/authors_info/${author.id}`}>Подробнее</Link>
                    </div>
                ))
            ) : (
                <p>Авторы не найдены.</p>
            )}
        </div>
    );
};

export default Authors;