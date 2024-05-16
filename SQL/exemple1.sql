-- Active: 1715847444276@@127.0.0.1@3306@exemple
select * from employe;
select * from dept;

select *
from dept d
join employe e on e.nodep=d.nodept;

select *
from dept, employe
where dept.nodept=employe.nodep;


select d.nom, e.nom, e.titre, e.salaire
from dept d
join employe e on e.nodep=d.nodept
where salaire>30000 and e.nom like 'z%';

select e.nom
from dept d
join employe e on e.nodep=d.nodept
where d.nom='vente';

select nom from employe where nodep in (
    select nodept from dept where nom='vente'
);



select employe.prenom, patron.prenom
from employe
join employe patron on employe.nosup=patron.noemp;





select *
from dept d left join employe e on e.nodep=d.nodept;

select *
from employe e right join dept d on e.nodep=d.nodept;

select * 
from employe
outer JOIN dept;




select * from dept;


select titre, count(*) as 'nombre', sum(salaire) as 'somme', avg(salaire)
from employe
where year(dateemb)=2000
group by titre
having titre like 'dir.%'
order by somme desc
limit 0,4;


select * from employe;