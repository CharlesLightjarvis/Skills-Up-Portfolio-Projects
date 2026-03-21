import { SkeletonGroup, TagGroup } from "heroui-native";
import React, { useEffect } from "react";
import { ScrollView } from "react-native";
import { useCategories } from "../hooks/use-categories";
import { Category } from "../types/category";

interface CategoryFilterProps {
  selected?: string;
  onSelect: (slug: string) => void;
}

const CategoryFilter = ({ selected, onSelect }: CategoryFilterProps) => {
  const { data: categories, isLoading } = useCategories();

  useEffect(() => {
    if (categories?.[0] && !selected) {
      onSelect(categories[0].slug);
    }
  }, [categories]);

  return (
    <ScrollView
      horizontal
      showsHorizontalScrollIndicator={false}
      className="mb-4"
    >
      {isLoading ? (
        <SkeletonGroup isLoading className="flex-row gap-2">
          {Array.from({ length: 5 }).map((_, i) => (
            <SkeletonGroup.Item key={i} className="h-8 w-20 rounded-full" />
          ))}
        </SkeletonGroup>
      ) : (
        <TagGroup
          selectionMode="single"
          selectedKeys={selected ? [selected] : []}
          onSelectionChange={(keys) => {
            const key = Array.from(keys)[0] as string;
            if (key) onSelect(key);
          }}
        >
          <TagGroup.List>
            {categories.map((category: Category) => (
              <TagGroup.Item key={category.id} id={category.slug}>
                {category.name}
              </TagGroup.Item>
            ))}
          </TagGroup.List>
        </TagGroup>
      )}
    </ScrollView>
  );
};

export default CategoryFilter;
