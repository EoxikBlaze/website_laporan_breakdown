"use client";

import { ChevronLeft, ChevronRight } from "lucide-react";
import * as React from "react";
import { DayPicker } from "react-day-picker";

import { cn } from "@/lib/utils";
import { buttonVariants } from "@/components/ui/button";

export type CalendarProps = React.ComponentProps<typeof DayPicker>;

function Calendar({
  className,
  classNames,
  showOutsideDays = true,
  components: usercomponents,
  ...props
}: CalendarProps) {
  const defaultClassNames = {
    months: "relative flex flex-col sm:flex-row gap-4",
    month: "w-full",
    month_caption: "relative mx-10 mb-2 flex h-9 items-center justify-center z-20",
    caption_label: "text-sm font-semibold",
    nav: "absolute top-0 flex w-full justify-between z-10",
    button_previous: cn(
      buttonVariants({ variant: "ghost" }),
      "size-9 text-muted-foreground/80 hover:text-foreground p-0 rounded-lg",
    ),
    button_next: cn(
      buttonVariants({ variant: "ghost" }),
      "size-9 text-muted-foreground/80 hover:text-foreground p-0 rounded-lg",
    ),
    month_grid: "w-full border-collapse",
    weekdays: "flex",
    weekday: "size-9 p-0 text-xs font-medium text-muted-foreground/80 flex items-center justify-center",
    week: "flex w-full mt-1",
    day: "group size-9 p-0 text-sm flex items-center justify-center relative",
    day_button: cn(
        "relative flex size-8 items-center justify-center whitespace-nowrap rounded-full p-0 text-foreground transition-all duration-200 focus:outline-none hover:bg-accent",
        "group-data-[selected]:bg-primary group-data-[selected]:text-primary-foreground group-data-[selected]:font-bold group-data-[selected]:shadow-lg group-data-[selected]:shadow-primary/30",
        "group-data-[disabled]:pointer-events-none group-data-[disabled]:opacity-30",
        "group-data-[outside]:opacity-30"
    ),
    today:
      "*:after:pointer-events-none *:after:absolute *:after:bottom-1 *:after:start-1/2 *:after:z-10 *:after:size-[3px] *:after:-translate-x-1/2 *:after:rounded-full *:after:bg-primary [&[data-selected]:not(.range-middle)>*]:after:bg-background *:after:transition-colors",
    outside: "text-muted-foreground data-selected:bg-accent/50 data-selected:text-muted-foreground",
    hidden: "invisible",
    week_number: "size-9 p-0 text-xs font-medium text-muted-foreground/80",
  };

  const mergedClassNames: typeof defaultClassNames = Object.keys(defaultClassNames).reduce(
    (acc, key) => ({
      ...acc,
      [key]: classNames?.[key as keyof typeof classNames]
        ? cn(
            defaultClassNames[key as keyof typeof defaultClassNames],
            classNames[key as keyof typeof classNames],
          )
        : defaultClassNames[key as keyof typeof defaultClassNames],
    }),
    {} as typeof defaultClassNames,
  );

  const defaultcomponents = {
    Chevron: (props: any) => {
      if (props.orientation === "left") {
        return <ChevronLeft size={16} strokeWidth={2} {...props} aria-hidden="true" />;
      }
      return <ChevronRight size={16} strokeWidth={2} {...props} aria-hidden="true" />;
    },
  };

  const mergedcomponents = {
    ...defaultcomponents,
    ...usercomponents,
  };

  return (
    <DayPicker
      showOutsideDays={showOutsideDays}
      className={cn("w-fit", className)}
      classNames={mergedClassNames}
      components={mergedcomponents}
      {...props}
    />
  );
}
Calendar.displayName = "Calendar";

export { Calendar };
