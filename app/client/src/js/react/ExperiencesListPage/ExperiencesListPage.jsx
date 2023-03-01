

const ExperiencesListPage = () => {
    return (
        <div className="section section--experiencesoverview">
            <div className="section_content">
                <div className="location_sidebar">
                    <ExperienceCardSidebar />
                </div>
                <div className="location_experiences">
                    <ExperienceCardFilter />
                    <ExperienceCardList />
                </div>
            </div>
        </div>
    )
}

export default ExperiencesListPage;
